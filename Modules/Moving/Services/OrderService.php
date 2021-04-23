<?php

namespace Modules\Moving\Services;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Moving\Entities\Address;
use Modules\Moving\Entities\OrderRoom;
use Modules\Moving\Entities\Room;
use Modules\Moving\Repositories\Contracts\OrderRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Throwable;

class OrderService extends Controller
{
    private $repo;
    private $customerService;

    public function __construct(OrderRepositoryInterface $repo, CustomerService $customerService)
    {
        $this->repo            = $repo;
        $this->customerService = $customerService;
    }

    /**
     * Display a listing of the resource.
     * @return array
     */
    public function index(bool $getData)
    {
        if ($getData) {
            $orders = $this->repo->relationships(
                'address',
                'customer',
                'user',
                'orderRooms.room',
                'orderRooms.items.packing',
                'orderRooms.images'
            )->getAll();
        } else {
            $orders = $this->repo->relationships()->getAll();
        }

        return $orders;
    }

    /**
     * Store a newly created resource in storage.
     * @param array $request
     * @return Order
     */
    public function store(array $data)
    {
        try {
            DB::beginTransaction();

            if (!empty($data['address_data'])) {
                $address            = Address::create($data['address_data']);
                $data['address_id'] = $address->id;
            } else {
                $customer           = $this->customerService->show($data['customer_id']);
                $data['address_id'] = $customer->primary_address_id;
            }

            $order = $this->repo->create($data);

            if (!empty($data['rooms'])) {
                $roomsData = collect($data['rooms']);
                $rooms_ids = $this->getRoomsIds($roomsData);

                $order->rooms()->sync($rooms_ids);
                foreach ($order->rooms as $index => $room) {
                    $this->syncOrderItems($room, $roomsData[$index]);
                }
            }

            DB::commit();
        } catch (Throwable $ex) {
            DB::rollBack();
            throw new Exception("error on creating order: {$ex->getMessage()}");
        }

        return $order;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Order
     */
    public function show(string $id, bool $getData)
    {
        if ($getData) {
            $order = $this->repo->relationships(
                'address',
                'customer',
                'user',
                'orderRooms.room',
                'orderRooms.items.packing',
                'orderRooms.images'
            )->findById($id);
        } else {
            $order = $this->repo->findById($id);
        }

        return $order;
    }

    /**
     * Update the specified resource in storage.
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, $id)
    {
        try {
            DB::beginTransaction();

            if (!empty($data['address_data']) && !empty($data['address_id'])) {
                Address::findOrFail($data['address_id'])->update($data['address_data']);
            }

            $order = $this->show($id, true);

            if (!empty($data['rooms'])) {
                $roomsData = collect($data['rooms']);
                $rooms_ids = $this->getRoomsIds($roomsData);

                $order->rooms()->detach();
                $order->rooms()->sync($rooms_ids);

                foreach ($order->rooms as $index => $room) {
                    $this->syncOrderItems($room, $roomsData[$index]);
                }
            }

            $update = $this->repo->update($data, $id);
            $order->touch();

            DB::commit();
        } catch (Throwable $ex) {
            DB::rollBack();
            if ($ex instanceof ModelNotFoundException) {
                throw new ModelNotFoundException($ex->getMessage());
            } else {
                throw new Exception("error on updating order: {$ex->getMessage()}");
            }
        }

        return $update;
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return bool
     */
    public function destroy($id)
    {
        return $this->repo->delete($id);
    }

    /**
     * get all rooms ids for sync
     *
     * @param \Illuminate\Support\Collection $rooms
     *
     * @return array
     */
    public function getRoomsIds(Collection $rooms)
    {
        $rooms_ids = [];

        foreach ($rooms as $room) {
            // $rooms_ids[$room['room_id']] = ['obs' => $room['obs'] ?? null];
            // push array to make possible room of the same id on order
            array_push($rooms_ids, ['room_id' => $room['room_id'],'obs' => $room['obs'] ?? null]);
        }

        return $rooms_ids;
    }

    /**
     * get all items ids for sync
     *
     * @param \Illuminate\Support\Collection $items
     *
     * @return array
     */
    private function getItemsIds(Collection $items)
    {
        $items_ids = [];

        foreach ($items as $item) {
            array_push($items_ids, [
                'item_id' => $item['item_id'],'obs' => $item['obs'] ?? null, 'quantity' => $item['quantity']
            ]);
        }

        return $items_ids;
    }

    /**
     * Store images on order
     *
     * @param Room $room
     * @param \Illuminate\Support\Collection $images
     *
     * @return void
     */
    private function storeImages(Room $room, Collection $images)
    {
        foreach ($images as $image) {
            $room->images()->create([
                'image'         => $image['image'],
                'order_room_id' => $room->pivot->id
            ]);
        }
    }

    /**
     * sync all items ids and store images
     *
     * @param \Modules\Moving\Entities\OrderRoom $room
     * @param \Illuminate\Support\Collection $rooms
     * @param boolean $deleteImages
     *
     * @return void
     */
    public function syncOrderItems(Room $room, array $roomData)
    {
        if (!empty($roomData['items'])) {
            $items = collect($roomData['items']);

            $items_ids = $this->getItemsIds($items);

            $order_room = OrderRoom::find($room->pivot->id);

            $order_room->items()->sync($items_ids);
        }

        if (!empty($roomData['images'])) {
            // already deletead on detach order_room because cascade database
            // if ($deleteImages) {
            //     $room->images()->delete();
            // }

            $images = collect($roomData['images']);

            $this->storeImages($room, $images);
        }
    }
}

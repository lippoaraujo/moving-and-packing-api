<?php

namespace Modules\Moving\Database\Seeders\Start;

use Illuminate\Http\Response;
use Illuminate\Database\Seeder;
use Modules\System\Entities\User;
use Modules\Moving\Entities\Order;
use Modules\System\Entities\Tenant;
use Modules\Moving\Entities\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Modules\System\Database\Seeders\Exceptions\SeedUsersNotFound;
use Modules\System\Database\Seeders\Exceptions\SeedTenantNotFound;
use Modules\Moving\Database\Seeders\Exceptions\SeedCustomerNotFound;
use Modules\Moving\Database\Seeders\Exceptions\SeedOrder;
use Modules\Moving\Entities\Image;
use Modules\Moving\Entities\OrderRoom;
use Modules\Moving\Entities\Room;
use Modules\Moving\Services\OrderService;
use Throwable;

class OrderTableSeeder extends Seeder
{
    private $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        try {
            DB::beginTransaction();

            $tenant = Tenant::first();

            if(!empty($tenant)) {
                $customer = Customer::with('primaryAddress')->first();
                $user = User::first();

                if(empty($user)) {
                    $message = 'ERROR: User not found!';
                    $this->command->error($message);
                    throw new SeedUsersNotFound($message, Response::HTTP_NOT_FOUND);
                }

                if(!empty($customer)) {

                    $address = $customer->primaryAddress;

                    $order = Order::factory()->create([
                        'address_id'    => $address->id,
                        'customer_id'   => $customer->id,
                        'user_id'       => $user->id,
                        'tenant_id'     => $tenant->id
                    ]);

                    $this->command->warn("==================================");
                    $this->command->info("INFO: Order was created: {$order->id} - {$order->ordered_at}");
                    $this->command->warn("INFO: #### address: {$address->id}");
                    $this->command->warn("INFO: #### customer: {$customer->id}");
                    $this->command->warn("INFO: #### user: {$user->id}");
                    $this->command->warn("INFO: #### user: {$user->id}");
                    $this->command->warn("==================================");

                    $data = Order::factory()->getExtraData()->make()->toArray();

                    if(!empty($data['rooms'])) {
                        $roomsData = collect($data['rooms']);
                        $rooms_ids = $this->orderService->getRoomsIds($roomsData);

                        $order->rooms()->sync($rooms_ids);

                        foreach ($order->rooms as $room) {
                            $this->syncOrderItems($room, $roomsData, $tenant);
                        }

                        // foreach($order->rooms as $room) {

                        //     foreach($rooms as $roomData) {

                        //         if($room->id === $roomData['room_id'] && !empty($roomData['items'])) {
                        //             $items_ids = array();

                        //             $items = collect($roomData['items']);

                        //             foreach($items as $item) {
                        //                 $items_ids[$item['item_id']] = ['obs' => $item['obs'] ?? null];
                        //             }

                        //             $order_room = OrderRoom::find($room->pivot->id);
                        //             $order_room->items()->sync($items_ids);
                        //         }

                        //         if($room->id === $roomData['room_id'] && !empty($roomData['images'])) {
                        //             $images = collect($roomData['images']);

                        //             foreach($images as $image) {

                        //                 $room->images()->create([
                        //                     'image'         => $image['image'],
                        //                     'order_room_id' => $room->pivot->id,
                        //                     'tenant_id'     => $tenant->id
                        //                 ]);
                        //             }
                        //         }
                        //     }
                        // }
                    }

                } else {
                    $message = 'ERROR: Customer not found!';
                    $this->command->error($message);
                    throw new SeedCustomerNotFound($message, Response::HTTP_NOT_FOUND);
                }
            } else {
                $message = 'ERROR: Tenant not found!';
                $this->command->error($message);
                throw new SeedTenantNotFound($message, Response::HTTP_NOT_FOUND);
            }

            DB::commit();
        } catch(Throwable $ex) {
            DB::rollBack();
            throw new SeedOrder($ex->getMessage());
        }
    }

    private function syncOrderItems(Room $room, Collection $rooms, Tenant $tenant)
    {
        foreach($rooms as $roomData) {

            if($room->id === $roomData['room_id'] && !empty($roomData['items'])) {
                $items_ids = array();

                $items = collect($roomData['items']);

                foreach($items as $item) {
                    $items_ids[$item['item_id']] = ['obs' => $item['obs'] ?? null, 'quantity' => $item['quantity']];
                }

                $order_room = OrderRoom::find($room->pivot->id);
                $order_room->items()->sync($items_ids);
            }

            if($room->id === $roomData['room_id'] && !empty($roomData['images'])) {
                $images = collect($roomData['images']);

                foreach($images as $image) {

                    $room->images()->create([
                        'image'         => $image['image'],
                        'order_room_id' => $room->pivot->id,
                        'tenant_id'     => $tenant->id
                    ]);
                }
            }
        }
    }
}

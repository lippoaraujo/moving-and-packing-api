<?php

namespace Modules\Moving\Services;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Moving\Entities\Address;
use Modules\Moving\Repositories\Contracts\CustomerRepositoryInterface;

class CustomerService extends Controller
{
    private $repo;

    public function __construct(CustomerRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }
    /**
     * Display a listing of the resource.
     * @return array
     */
    public function index()
    {
        return $this->repo->relationships('primaryAddress')->getAll()->toArray();
    }

    /**
     * Store a newly created resource in storage.
     * @param array $request
     * @return Customer
     */
    public function store(array $data)
    {
        try {
            DB::beginTransaction();
            $customer = $this->repo->create($data);

            if (!empty($data['address'])) {
                // $address = $customer->adresses()->create($data);
                $address = Address::create($data);
                $customer->setprimaryAddress($address);
                $customer->primary_address_id = $address->id;
                $customer->save();
                $customer->load('primaryAddress');
            }
            DB::commit();
        } catch (Throwable $ex) {
            DB::rollback();
            throw new Exception("error on creating customer: {$ex->getMessage()}");
        }

        return $customer;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Customer
     */
    public function show(string $id)
    {
        return $this->repo->relationships('primaryAddress')->findById($id);
    }

    /**
     * Update the specified resource in storage.
     * @param array $data
     * @param int $id
     * @return bool
     */
    public function update(array $data, $id)
    {
        $this->show($id);
        try {
            DB::beginTransaction();

            $this->repo->update($data, $id);
            $customer = $this->show($id);
            $customer->primaryAddress->update($data);

            DB::commit();
        } catch (Throwable $ex) {
            DB::rollback();

            if ($ex instanceof ModelNotFoundException) {
                throw new ModelNotFoundException($ex->getMessage());
            } else {
                throw new Exception("error on updating customer: {$ex->getMessage()}");
            }
        }

        return true;
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
}

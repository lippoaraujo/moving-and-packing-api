<?php

namespace Modules\Moving\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Moving\Entities\Customer;

class CustomerTest extends TestCase
{
    use DatabaseTransactions;

    private const ROUTE_URL = "/api/v1/moving/customers";

    /**
     * @test
     */
    public function given_customer_data_when_posting_returns_customer_stored()
    {

        $headers = $this->headers($this->getUserAdmin());

        $data = $this->getData();

        $response = $this->withHeaders($headers)
        ->json('POST', self::ROUTE_URL, $data);

        $response->assertCreated();
        $response->assertJsonStructure($this->getJsonStructure());
    }

    /**
     * @test
     */
    public function given_customer_data_with_address_when_posting_returns_customer_stored_with_address()
    {
        $headers = $this->headers($this->getUserAdmin());

        $data = $this->getData(true);

        $response = $this->withHeaders($headers)
        ->json('POST', self::ROUTE_URL, $data);

        $response->assertCreated();
        $response->assertJsonStructure($this->getJsonStructure(false, true));
    }

    /**
     * @test
     */
    public function given_incomplete_customer_data_when_posting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());
        $data = $this->getData();

        if(isset($data['name'])) {
            unset($data['name']);
        }

        $response = $this->withHeaders($headers)
        ->json('POST', self::ROUTE_URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message']);
    }

    /**
     * @test
     */
    public function given_noid_when_getting_customer_returns_all_customers_data()
    {
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('GET', self::ROUTE_URL);

        $response->assertOk();
        $response->assertJsonStructure($this->getJsonStructure(true));
    }

    /**
     * @test
     */
    public function given_valid_id_when_getting_customer_returns_a_customer_data()
    {
        $customer = $this->getValidCustomer();

        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('GET', $this->getRouteId(self::ROUTE_URL, $customer->id));

        $response->assertOk();
        $response->assertJsonStructure($this->getJsonStructure());
    }

    /**
     * @test
     */
    public function given_notvalid_id_when_getting_customer_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('GET', $this->getRouteId(self::ROUTE_URL));

        $response->assertNotFound();
        $response->assertJsonStructure(['message',]);
    }

    /**
     * @test
     */
    public function given_customer_data_withvalid_id_when_putting_returns_true()
    {
        $headers = $this->headers($this->getUserAdmin());
        $customer = $this->getValidCustomer();
        $customer->name = 'name updated';
        $data = $customer->toArray();

        $response = $this->withHeaders($headers)
        ->json('PUT', $this->getRouteId(self::ROUTE_URL, $customer->id), $data);

        $response->assertOk();
        $response->assertExactJson(['data' => true]);
    }

     /**
     * @test
     */
    public function given_customer_data_with_notvalid_id_when_putting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());
        $customer = $this->getValidCustomer();
        $customer->name = 'name updated';
        $data = $customer->toArray();

        $response = $this->withHeaders($headers)
        ->json('PUT', $this->getRouteId(self::ROUTE_URL), $data);

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    /**
     * @test
     */
    public function given_valid_customer_id_when_deleting_returns_true()
    {
        $customer = $this->getValidCustomer();
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('DELETE', $this->getRouteId(self::ROUTE_URL, $customer->id));

        $response->assertOk();
        $response->assertExactJson(['data' => true]);
    }

    /**
     * @test
     */
    public function given_notvalid_customer_id_when_deleting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('DELETE', $this->getRouteId(self::ROUTE_URL));

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    private function getData(bool $address = false)
    {
        if($address) {
            $customer = Customer::factory()->getAddressData()->make();
        } else {
            $customer = Customer::factory()->make();
        }
            $data = $customer->toArray();
        return $data;
    }

    private function getValidcustomer(bool $toArray = false)
    {
        $customer =  Customer::all()->first();

        if($toArray) {
            $customer = $customer->toArray();
        }

        return $customer;
    }

    private function getJsonStructure(bool $hasMany = false, bool $address = false)
    {
        if($hasMany) {
            $json = [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'tenant_id',
                    'active',
                    'primary_address',
                ]];
        } elseif($address) {
            $json = [
                'id',
                'name',
                'active','id',
                'name',
                'email',
                'phone',
                'tenant_id',
                'active',
                'primary_address'
            ];
        } else {
            $json = [
                'id',
                'name',
                'active','id',
                'name',
                'email',
                'phone',
                'tenant_id',
                'active',
            ];
        }

        $data['data'] = $json;
        return $data;
    }
}

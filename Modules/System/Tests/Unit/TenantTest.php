<?php

namespace Modules\System\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\System\Entities\Tenant;

class TenantTest extends TestCase
{
    use DatabaseTransactions;

    private const ROUTE_URL = "/api/v1/system/tenants";

    /**
     * @test
     */
    public function given_tenantdata_when_posting_returns_tenant_stored()
    {
        $headers = $this->headers($this->getUserAdmin());

        $data = $this->getData(true);
        $data['password'] = $data['password_confirmation'];

        $response = $this->withHeaders($headers)
        ->json('POST', self::ROUTE_URL, $data);

        $response->assertCreated();
        $response->assertJsonStructure($this->getJsonStructure(false, true));
    }

    /**
     * @test
     */
    public function given_incomplete_tenantdata_when_posting_returns_error()
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
    public function given_noid_when_getting_tenant_returns_all_tenants_data()
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
    public function given_validid_when_getting_tenant_returns_a_tenant_data()
    {
        $tenant = $this->getValidTenant();

        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('GET', $this->getRouteId(self::ROUTE_URL, $tenant->id));

        $response->assertOk();
        $response->assertJsonStructure($this->getJsonStructure());
    }

    /**
     * @test
     */
    public function given_notvalid_id_when_getting_tenant_returns_error()
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
    public function given_tenantdata_withvalid_id_when_putting_returns_true()
    {
        $headers = $this->headers($this->getUserAdmin());
        $tenant = $this->getValidTenant();
        $tenant->name = 'name updated';
        $data = $tenant->toArray();

        $response = $this->withHeaders($headers)
        ->json('PUT', $this->getRouteId(self::ROUTE_URL, $tenant->id), $data);

        $response->assertOk();
        $response->assertExactJson(['data' => true]);
    }

     /**
     * @test
     */
    public function given_tenantdata_withnotvalid_id_when_putting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());
        $tenant = $this->getValidTenant();
        $tenant->name = 'name updated';
        $data = $tenant->toArray();

        $response = $this->withHeaders($headers)
        ->json('PUT', $this->getRouteId(self::ROUTE_URL), $data);

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    /**
     * @test
     */
    public function given_valid_tenant_id_when_deleting_returns_true()
    {
        $tenant = $this->getValidTenant();

        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('DELETE', $this->getRouteId(self::ROUTE_URL, $tenant->id));

        $response->assertOk();
        $response->assertExactJson(['data' => true]);
    }

    /**
     * @test
     */
    public function given_notvalid_tenant_id_when_deleting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('DELETE', $this->getRouteId(self::ROUTE_URL));

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    private function getData(bool $pass = false)
    {
        if($pass) {
            $tenant = Tenant::factory()->passConfirmed()->make();
        } else {
            $tenant = Tenant::factory()->make();
        }

        $data = $tenant->toArray();
        return $data;
    }

    private function getValidTenant(bool $toArray = false)
    {
        $tenant =  Tenant::all()->first();

        if($toArray) {
            $tenant = $tenant->toArray();
        }

        return $tenant;
    }

    private function getJsonStructure(bool $hasMany = false, bool $store = false)
    {
        if($hasMany) {
            $json = [
                '*' => [
                    'id',
                    'uuid',
                    'cnpj',
                    'name',
                    'trading_name',
                    'email',
                    'active',
                ]];
        } elseif($store) {
                $json = [
                    'id',
                    'uuid',
                    'cnpj',
                    'name',
                    'trading_name',
                    'email',
                    'active',
                    'default_usergroup' => [
                        'name',
                        'tenant_id',
                        'active'
                    ],
                    'default_user' => [
                        'name',
                        'email',
                        'usergroup_id',
                        'active'
                    ]
                ];
        } else {
            $json = [
                'id',
                'uuid',
                'cnpj',
                'name',
                'trading_name',
                'email',
                'active',
            ];
        }

        $data['data'] = $json;
        return $data;
    }
}

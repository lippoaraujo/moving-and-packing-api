<?php

namespace Modules\System\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\System\Entities\Usergroup;

class UsergroupTest extends TestCase
{
    use DatabaseTransactions;

    private const ROUTE_URL = "/api/v1/system/usergroups";

    /**
     * @test
     */
    public function given_usergroupdata_when_posting_returns_usergroup_stored()
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
    public function given_incomplete_usergroupdata_when_posting_returns_error()
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
    public function given_noid_when_getting_usergroup_returns_all_usergroups_data()
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
    public function given_valid_id_when_getting_usergroup_returns_a_usergroup_data()
    {
        $usergroup = $this->getValidUsergroup();

        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('GET', $this->getRouteId(self::ROUTE_URL, $usergroup->id));

        $response->assertOk();
        $response->assertJsonStructure($this->getJsonStructure());
    }

    /**
     * @test
     */
    public function given_notvalid_id_when_getting_usergroup_returns_error()
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
    public function given_usergroupdata_withvalid_id_when_putting_returns_true()
    {
        $headers = $this->headers($this->getUserAdmin());
        $usergroup = $this->getValidUsergroup();
        $usergroup->name = 'name updated';
        $data = $usergroup->toArray();

        $response = $this->withHeaders($headers)
        ->json('PUT', $this->getRouteId(self::ROUTE_URL, $usergroup->id), $data);

        $response->assertOk();
        $response->assertExactJson(['data' => true]);
    }

     /**
     * @test
     */
    public function given_usergroupdata_withnotvalid_id_when_putting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());
        $usergroup = $this->getValidUsergroup();
        $usergroup->name = 'name updated';
        $data = $usergroup->toArray();

        $response = $this->withHeaders($headers)
        ->json('PUT', $this->getRouteId(self::ROUTE_URL), $data);

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    /**
     * @test
     */
    public function given_valid_usergroup_id_when_deleting_returns_true()
    {
        $usergroup = $this->getValidUsergroup();

        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('DELETE', $this->getRouteId(self::ROUTE_URL, $usergroup->id));

        $response->assertOk();
        $response->assertExactJson(['data' => true]);
    }

    /**
     * @test
     */
    public function given_notvalid_usergroup_id_when_deleting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('DELETE', $this->getRouteId(self::ROUTE_URL));

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    private function getData()
    {
        $usergroup = Usergroup::factory()->make();
        $data = $usergroup->toArray();
        return $data;
    }

    private function getValidUsergroup(bool $toArray = false)
    {
        $usergroup =  Usergroup::all()->first();

        if($toArray) {
            $usergroup = $usergroup->toArray();
        }

        return $usergroup;
    }

    private function getJsonStructure(bool $hasMany = false)
    {
        if($hasMany) {
            $json = [
                '*' => [
                    'id',
                    'name',
                    'tenant_id',
                    'active'
                ]];
        } else {
            $json = [
                'id',
                'name',
                'tenant_id',
                'active'
            ];
        }

        $data['data'] = $json;
        return $data;
    }
}

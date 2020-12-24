<?php

namespace Modules\System\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\System\Entities\Action;

class ActionTest extends TestCase
{
    use DatabaseTransactions;

    private const ROUTE_URL = "/api/v1/system/actions";

    /**
     * @test
     */
    public function given_action_data_when_posting_returns_action_stored()
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
    public function given_incomplete_action_data_when_posting_returns_error()
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
    public function given_noid_when_getting_action_returns_all_actions_data()
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
    public function given_valid_id_when_getting_action_returns_a_action_data()
    {
        $action = $this->getValidAction();

        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('GET', $this->getRouteId(self::ROUTE_URL, $action->id));

        $response->assertOk();
        $response->assertJsonStructure($this->getJsonStructure());
    }

    /**
     * @test
     */
    public function given_notvalid_id_when_getting_action_returns_error()
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
    public function given_action_data_withvalid_id_when_putting_returns_true()
    {
        $headers = $this->headers($this->getUserAdmin());
        $action = $this->getValidAction();
        $action->name = 'name updated';
        $data = $action->toArray();

        $response = $this->withHeaders($headers)
        ->json('PUT', $this->getRouteId(self::ROUTE_URL, $action->id), $data);

        $response->assertOk();
        $response->assertExactJson(['data' => true]);
    }

     /**
     * @test
     */
    public function given_action_data_with_notvalid_id_when_putting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());
        $action = $this->getValidAction();
        $action->name = 'name updated';
        $data = $action->toArray();

        $response = $this->withHeaders($headers)
        ->json('PUT', $this->getRouteId(self::ROUTE_URL), $data);

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    /**
     * @test
     */
    public function given_valid_action_id_when_deleting_returns_true()
    {
        $action = $this->getValidAction();
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('DELETE', $this->getRouteId(self::ROUTE_URL, $action->id));

        $response->assertOk();
        $response->assertExactJson(['data' => true]);
    }

    /**
     * @test
     */
    public function given_notvalid_action_id_when_deleting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('DELETE', $this->getRouteId(self::ROUTE_URL));

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    private function getData()
    {
        $action = Action::factory()->make();
        $data = $action->toArray();
        return $data;
    }

    private function getValidAction(bool $toArray = false)
    {
        $action =  Action::all()->first();

        if($toArray) {
            $action = $action->toArray();
        }

        return $action;
    }

    private function getJsonStructure(bool $hasMany = false)
    {
        if($hasMany) {
            $json = [
                '*' => [
                    'id',
                    'name',
                    'type',
                    'active',
                ]];
        } else {
            $json = [
                'id',
                'name',
                'type',
                'active',
            ];
        }

        $data['data'] = $json;
        return $data;
    }
}

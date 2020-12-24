<?php

namespace Modules\Moving\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Moving\Entities\Room;

class RoomTest extends TestCase
{
    use DatabaseTransactions;

    private const ROUTE_URL = "/api/v1/moving/rooms";

    /**
     * @test
     */
    public function given_room_data_when_posting_returns_room_stored()
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
    public function given_incomplete_room_data_when_posting_returns_error()
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
    public function given_noid_when_getting_room_returns_all_rooms_data()
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
    public function given_valid_id_when_getting_room_returns_a_room_data()
    {
        $room = $this->getValidRoom();

        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('GET', $this->getRouteId(self::ROUTE_URL, $room->id));

        $response->assertOk();
        $response->assertJsonStructure($this->getJsonStructure());
    }

    /**
     * @test
     */
    public function given_notvalid_id_when_getting_room_returns_error()
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
    public function given_room_data_withvalid_id_when_putting_returns_true()
    {
        $headers = $this->headers($this->getUserAdmin());
        $room = $this->getValidRoom();
        $room->name = 'name updated';
        $data = $room->toArray();

        $response = $this->withHeaders($headers)
        ->json('PUT', $this->getRouteId(self::ROUTE_URL, $room->id), $data);

        $response->assertOk();
        $response->assertExactJson(['data' => true]);
    }

     /**
     * @test
     */
    public function given_room_data_with_notvalid_id_when_putting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());
        $room = $this->getValidRoom();
        $room->name = 'name updated';
        $data = $room->toArray();

        $response = $this->withHeaders($headers)
        ->json('PUT', $this->getRouteId(self::ROUTE_URL), $data);

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    /**
     * @test
     */
    public function given_valid_room_id_when_deleting_returns_true()
    {
        $room = $this->getValidRoom();
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('DELETE', $this->getRouteId(self::ROUTE_URL, $room->id));

        $response->assertOk();
        $response->assertExactJson(['data' => true]);
    }

    /**
     * @test
     */
    public function given_notvalid_room_id_when_deleting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('DELETE', $this->getRouteId(self::ROUTE_URL));

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    private function getData()
    {
        $room = Room::factory()->make();
        $data = $room->toArray();
        return $data;
    }

    private function getValidroom(bool $toArray = false)
    {
        $room =  Room::all()->first();

        if($toArray) {
            $room = $room->toArray();
        }

        return $room;
    }

    private function getJsonStructure(bool $hasMany = false)
    {
        if($hasMany) {
            $json = [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'tenant_id',
                    'active',
                ]];
        } else {
            $json = [
                'id',
                'name',
                'description',
                'tenant_id',
                'active',
            ];
        }

        $data['data'] = $json;
        return $data;
    }
}

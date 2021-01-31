<?php

namespace Modules\Moving\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Moving\Entities\Item;
use Modules\Moving\Entities\Packing;
use Modules\Moving\Entities\Room;

class ItemTest extends TestCase
{
    use DatabaseTransactions;

    private const ROUTE_URL = "/api/v1/moving/items";

    /**
     * @test
     */
    public function given_item_room_data_when_posting_returns_item_stored()
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
    public function given_incomplete_item_data_when_posting_returns_error()
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
    public function given_noid_when_getting_item_returns_all_items_data()
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
    public function given_valid_id_when_getting_item_returns_a_item_data()
    {
        $item = $this->getValidItem();

        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('GET', $this->getRouteId(self::ROUTE_URL, $item->id));

        $response->assertOk();
        $response->assertJsonStructure($this->getJsonStructure());
    }

    /**
     * @test
     */
    public function given_notvalid_id_when_getting_item_returns_error()
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
    public function given_item_data_withvalid_id_when_putting_returns_true()
    {
        $headers = $this->headers($this->getUserAdmin());
        $item = $this->getValidItem();
        $item->name = 'name updated';
        $data = $item->toArray();

        $response = $this->withHeaders($headers)
        ->json('PUT', $this->getRouteId(self::ROUTE_URL, $item->id), $data);

        $response->assertOk();
        $response->assertExactJson(['data' => true]);
    }

     /**
     * @test
     */
    public function given_item_data_with_notvalid_id_when_putting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());
        $item = $this->getValidItem();
        $item->name = 'name updated';
        $data = $item->toArray();

        $response = $this->withHeaders($headers)
        ->json('PUT', $this->getRouteId(self::ROUTE_URL), $data);

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    /**
     * @test
     */
    public function given_valid_item_id_when_deleting_returns_true()
    {
        $item = $this->getValidItem();
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('DELETE', $this->getRouteId(self::ROUTE_URL, $item->id));

        $response->assertOk();
        $response->assertExactJson(['data' => true]);
    }

    /**
     * @test
     */
    public function given_notvalid_item_id_when_deleting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('DELETE', $this->getRouteId(self::ROUTE_URL));

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    private function getData()
    {
        $packing = Packing::first();

        $item = Item::factory()->make([
            'packing_id' => $packing
        ]);

        return $item->toArray();
    }

    private function getValiditem(bool $toArray = false)
    {
        $item =  Item::all()->first();

        if($toArray) {
            $item = $item->toArray();
        }

        return $item;
    }

    private function getJsonStructure(bool $hasMany = false, bool $noRelations = false)
    {
        if($hasMany) {
            $json = [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'cubic_feet',
                    'tag',
                    'packing_qty',
                    'packing_id',
                    'tenant_id',
                    'packing' => [
                        'id',
                        'name',
                        'unity'
                    ]
                ]];
        } elseif($noRelations) {
            $json = [
                'id',
                'name',
                'description',
                'cubic_feet',
                'tag',
                'packing_qty',
                'packing_id',
                'tenant_id',
            ];
        } else {
            $json = [
                'id',
                'name',
                'description',
                'cubic_feet',
                'tag',
                'packing_qty',
                'packing_id',
                'tenant_id',
                'packing' => [
                    'id',
                    'name',
                    'unity'
                ]
            ];
        }

        $data['data'] = $json;
        return $data;
    }
}

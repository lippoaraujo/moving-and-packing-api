<?php

namespace Modules\Moving\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Moving\Entities\Image;
use Modules\Moving\Entities\OrderRoom;

class ImageTest extends TestCase
{
    use DatabaseTransactions;

    private const ROUTE_URL = "/api/v1/moving/images";

    /**
     * @test
     */
    public function given_image_data_when_posting_returns_image_stored()
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
    public function given_incomplete_image_data_when_posting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());
        $data = $this->getData();

        if(isset($data['image'])) {
            unset($data['image']);
        }

        $response = $this->withHeaders($headers)
        ->json('POST', self::ROUTE_URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message']);
    }

    /**
     * @test
     */
    public function given_noid_when_getting_image_returns_all_images_data()
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
    public function given_valid_id_when_getting_image_returns_a_image_data()
    {
        $image = $this->getValidImage();

        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('GET', $this->getRouteId(self::ROUTE_URL, $image->id));

        $response->assertOk();
        $response->assertJsonStructure($this->getJsonStructure());
    }

    /**
     * @test
     */
    public function given_notvalid_id_when_getting_image_returns_error()
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
    public function given_image_data_withvalid_id_when_putting_returns_true()
    {
        $headers = $this->headers($this->getUserAdmin());
        $image = $this->getValidImage();
        $image->image = 'updated';
        $data = $image->toArray();

        $response = $this->withHeaders($headers)
        ->json('PUT', $this->getRouteId(self::ROUTE_URL, $image->id), $data);

        $response->assertOk();
        $response->assertExactJson(['data' => true]);
    }

     /**
     * @test
     */
    public function given_image_data_with_notvalid_id_when_putting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());
        $image = $this->getValidImage();
        $image->image = 'updated';
        $data = $image->toArray();

        $response = $this->withHeaders($headers)
        ->json('PUT', $this->getRouteId(self::ROUTE_URL), $data);

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    /**
     * @test
     */
    public function given_valid_image_id_when_deleting_returns_true()
    {
        $image = $this->getValidImage();
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('DELETE', $this->getRouteId(self::ROUTE_URL, $image->id));

        $response->assertOk();
        $response->assertExactJson(['data' => true]);
    }

    /**
     * @test
     */
    public function given_notvalid_image_id_when_deleting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('DELETE', $this->getRouteId(self::ROUTE_URL));

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    private function getData()
    {
        $order_room = OrderRoom::first();
        $image = Image::factory()->make([
            'order_room_id' => $order_room->id
        ]);
        $data = $image->toArray();
        return $data;
    }

    private function getValidimage(bool $toArray = false)
    {
        $image =  Image::all()->first();

        if($toArray) {
            $image = $image->toArray();
        }

        return $image;
    }

    private function getJsonStructure(bool $hasMany = false)
    {
        if($hasMany) {
            $json = [
                '*' => [
                    'id',
                    'image',
                    'order_room_id',
                    'tenant_id',
                ]];
        } else {
            $json = [
                'id',
                'image',
                'order_room_id',
                'tenant_id',
            ];
        }

        $data['data'] = $json;
        return $data;
    }
}

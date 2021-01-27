<?php

namespace Modules\Moving\Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Modules\Moving\Entities\Customer;
use Modules\Moving\Entities\Order;

class OrderTest extends TestCase
{
    use DatabaseTransactions;

    private const ROUTE_URL = "/api/v1/moving/orders";

    /**
     * @test
     */
    public function given_order_data_when_posting_returns_order_stored()
    {
        $user = $this->getUserAdmin();
        $headers = $this->headers($user);

        $data = $this->getData($user->id, true);

        $response = $this->withHeaders($headers)
        ->json('POST', self::ROUTE_URL, $data);

        $response->assertCreated();
        $response->assertJsonStructure($this->getJsonStructure(false, true));
    }

    /**
     * @test
     */
    public function given_incomplete_order_data_when_posting_returns_error()
    {
        $user = $this->getUserAdmin();
        $headers = $this->headers($user);
        $data = $this->getData($user->id);

        if(isset($data['price'])) {
            unset($data['price']);
        }

        $response = $this->withHeaders($headers)
        ->json('POST', self::ROUTE_URL, $data);

        $response->assertStatus(422);
        $response->assertJsonStructure(['message']);
    }

    /**
     * @test
     */
    public function given_noid_when_getting_order_returns_all_orders_data()
    {
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('GET', self::ROUTE_URL, ['get_data'=> true]);

        $response->assertOk();
        $response->assertJsonStructure($this->getJsonStructure(true));
    }

    /**
     * @test
     */
    public function given_valid_id_when_getting_order_returns_a_order_data()
    {
        $order = $this->getValidOrder();

        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('GET', $this->getRouteId(self::ROUTE_URL, $order->getKey()), ['get_data'=> true]);

        $response->assertOk();
        $response->assertJsonStructure($this->getJsonStructure());
    }

    /**
     * @test
     */
    public function given_notvalid_id_when_getting_order_returns_error()
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
    public function given_order_data_withvalid_id_when_putting_returns_true()
    {
        $headers = $this->headers($this->getUserAdmin());
        $order = $this->getValidOrder();
        $order->customer_signature = 'updated';
        $data = $order->toArray();

        $response = $this->withHeaders($headers)
        ->json('PUT', $this->getRouteId(self::ROUTE_URL, $order->getKey()), $data);

        $response->assertOk();
        $response->assertExactJson(['data' => true]);
    }

     /**
     * @test
     */
    public function given_order_data_with_notvalid_id_when_putting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());
        $order = $this->getValidOrder();
        $order->customer_signature = 'updated';
        $data = $order->toArray();

        $response = $this->withHeaders($headers)
        ->json('PUT', $this->getRouteId(self::ROUTE_URL), $data);

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    /**
     * @test
     */
    public function given_valid_order_id_when_deleting_returns_true()
    {
        $order = $this->getValidOrder();
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('DELETE', $this->getRouteId(self::ROUTE_URL, $order->getKey()));

        $response->assertOk();
        $response->assertExactJson(['data' => true]);
    }

    /**
     * @test
     */
    public function given_notvalid_order_id_when_deleting_returns_error()
    {
        $headers = $this->headers($this->getUserAdmin());

        $response = $this->withHeaders($headers)
        ->json('DELETE', $this->getRouteId(self::ROUTE_URL));

        $response->assertNotFound();
        $response->assertJsonStructure(['message']);
    }

    private function getData($user_id, bool $withExtra = false)
    {
        $customer = Customer::with('primaryAddress')->first();
        if($withExtra) {
            $order = Order::factory()->getExtraData()->make([
                'customer_id'   =>  $customer->id,
                'user_id'       =>  $user_id
            ]);
        } else {
            $order = Order::factory()->make([
                'customer_id'   =>  $customer->id,
                'user_id'       =>  $user_id
            ]);
        }

        $data = $order->toArray();
        return $data;
    }

    private function getValidorder(bool $toArray = false)
    {
        $order =  Order::all()->first();

        if($toArray) {
            $order = $order->toArray();
        }

        return $order;
    }

    private function getJsonStructure(bool $hasMany = false, bool $noRelations = false)
    {
        if($hasMany) {
            $json = [
                '*' => [
                    'id',
                    'customer_signature',
                    'price',
                    'address_id',
                    'customer_id',
                    'user_id',
                    'expected_date',
                    'tenant_id',
                    'address' => [
                        'id',
                        'address',
                        'locality',
                        'city',
                        'postcode',
                        'country',
                        // 'customer_id',
                        'tenant_id',
                    ],
                    'customer' => [
                        'id',
                        'name',
                        'email',
                        'phone',
                        'primary_address_id',
                        'tenant_id'
                    ],
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'tenant_id',
                    ],
                    'order_rooms' => [
                        '*' => [
                            'id',
                            'order_id',
                            'room_id',
                            'obs',
                            'order_id',
                            'room' => [
                                'id',
                                'name',
                                'description'
                            ],
                            'items' => [
                                '*' => [
                                    'id',
                                    'name',
                                    'description',
                                    'cubic_feet',
                                    'tag',
                                    'pivot' => [
                                        'order_room_id',
                                        'item_id',
                                        'obs'
                                    ]
                                ]
                            ],
                            'images' => [
                                '*' => [
                                    'id',
                                    'image',
                                    'order_room_id',
                                ]
                            ]
                        ]
                    ]
                ]];
        } elseif($noRelations) {
            $json = [
                'id',
                'customer_signature',
                'price',
                'address_id',
                'customer_id',
                'user_id',
                'expected_date',
                'tenant_id',
            ];
        } else {
            $json = [
                'id',
                'customer_signature',
                'price',
                'address_id',
                'customer_id',
                'user_id',
                'expected_date',
                'tenant_id',
                'address' => [
                    'id',
                    'address',
                    'locality',
                    'city',
                    'postcode',
                    'country',
                    // 'customer_id',
                    'tenant_id',
                ],
                'customer' => [
                    'id',
                    'name',
                    'email',
                    'phone',
                    'primary_address_id',
                    'tenant_id'
                ],
                'user' => [
                    'id',
                    'name',
                    'email',
                    'tenant_id',
                ],
                'order_rooms' => [
                        '*' => [
                            'id',
                            'order_id',
                            'room_id',
                            'obs',
                            'order_id',
                            'room' => [
                                'id',
                                'name',
                                'description'
                            ],
                            'items' => [
                                '*' => [
                                    'id',
                                    'name',
                                    'description',
                                    'cubic_feet',
                                    'tag',
                                    'pivot' => [
                                        'order_room_id',
                                        'item_id',
                                        'obs'
                                    ]
                                ]
                            ],
                            'images' => [
                                '*' => [
                                    'id',
                                    'image',
                                    'order_room_id',
                                ]
                            ]
                        ]
                    ]
            ];
        }

        $data['data'] = $json;
        return $data;
    }
}

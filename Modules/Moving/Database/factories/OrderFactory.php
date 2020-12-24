<?php

namespace Modules\Moving\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Moving\Entities\Item;
use Modules\Moving\Entities\Order;
use Modules\Moving\Entities\Room;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
           'customer_signature' => base64_encode($this->faker->text()),
           'price'              => $this->faker->randomFloat(2, 1, 1000),
           'expected_date'      => $this->faker->date(),
        //    'address_id'         => $this->faker->randomFloat(2, 1, 1000),
        //    'customer_id'        => $this->faker->randomFloat(2, 1, 1000),
        //    'user_id'            => $this->faker->randomFloat(2, 1, 1000),
        ];
    }

    /**
     * add all order extra data.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function getExtraData()
    {
        // $room = Room::first();
        // $item = Item::first();
        return $this->state(function (array $attributes) {
            return [
                'address_data'  => [
                    'address'   => $this->faker->address,
                    'locality'  => $this->faker->locale,
                    'city'      => $this->faker->city,
                    'country'   => $this->faker->country,
                    'postcode'  => $this->faker->postcode,
                ],
                'rooms' => [
                    [
                        'room_id'   => 1,
                        'obs'       => 'lorem ipsum dolor',
                        'items'     =>  [
                            [
                                'item_id'   => 2,
                                'obs'       => 'lorem ipsum dolor'
                            ],
                            [
                                'item_id'   => 7,
                                'obs'       => 'lorem ipsum dolor'
                            ],
                            [
                                'item_id'   => 10,
                                'obs'       => 'lorem ipsum dolor'
                            ],
                        ],
                        'images'    => [
                            [
                                'image' => base64_encode($this->faker->text())
                            ]
                        ]
                    ],
                    [
                        'room_id'   => 2,
                        'obs'       => 'lorem ipsum dolor',
                        'items'     =>  [
                            [
                                'item_id'   => 3,
                                'obs'       => 'lorem ipsum dolor'
                            ]
                        ],
                        'images'    => [
                            [
                                'image' => base64_encode($this->faker->text())
                            ]
                        ]
                    ],
                    [
                        'room_id'   => 3,
                        'obs'       => null,
                        'items'     =>  [
                            [
                                'item_id'   => 5,
                                'obs'       => 'lorem ipsum dolor'
                            ]
                        ],
                        'images'    => [
                            [
                                'image' => base64_encode($this->faker->text())
                            ]
                        ]
                    ],
                    [
                        'room_id'   => 4,
                        'obs'       => $this->faker->text(),
                        'items'     =>  [
                            [
                                'item_id'   => 8,
                                'obs'       => 'lorem ipsum dolor'
                            ]
                        ],
                        'images'    => [
                            [
                                'image' => base64_encode($this->faker->text())
                            ]
                        ]
                    ]
                ]
            ];
        });
    }
}

<?php

namespace Modules\Moving\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Moving\Entities\Address;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'address'   => $this->faker->address,
            'locality'  => $this->faker->locale,
            'city'      => $this->faker->city,
            'country'   => $this->faker->country,
            'postcode'  => $this->faker->postcode,
        ];
    }
}

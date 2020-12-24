<?php

namespace Modules\Moving\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Moving\Entities\Customer;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'   => $this->faker->word(),
            'email'  => $this->faker->email,
            'phone'   => $this->faker->phoneNumber,
            // 'address_id',
            // 'tenant_id',
            'active' => $this->faker->randomElement([1])
        ];
    }

     /**
     * add address on data.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function getAddressData()
    {
        return $this->state(function (array $attributes) {
            return [
                'address'   => $this->faker->address,
                'locality'  => $this->faker->locale,
                'city'      => $this->faker->city,
                'country'   => $this->faker->country,
                'postcode'  => $this->faker->postcode,
            ];
        });
    }
}

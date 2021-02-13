<?php

namespace Modules\System\Database\factories;

use Illuminate\Support\Str;
use Modules\System\Entities\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\System\Entities\Plan;

class TenantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tenant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->company;
        return [
            // 'uuid'          => Str::uuid(),
            'cnpj'          => $this->faker->randomNumber(9),
            'name'          => $name,
            'trading_name'  => $name,
            'email'         => $this->faker->email,
            // 'active'        => $this->faker->randomElement([1]),
        ];
    }

    /**
     * Indicate that is a user confirmed password.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function passConfirmed()
    {
        $plan = Plan::first();
        $pass = $this->faker->password();
        return $this->state(function (array $attributes) use ($pass, $plan) {
            return [
                'password_confirmation' => $pass,
                'plan_id' => $plan->id,
            ];
        });
    }
}

<?php

namespace Modules\System\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\System\Entities\Action;

class ActionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Action::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name'   => $this->faker->word(),
            'type'   => $this->faker->randomElement(['GET', 'POST', 'PUT', 'DELETE']),
            'active' => $this->faker->randomElement([1]),
        ];
    }
}

<?php

namespace Modules\Moving\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Moving\Entities\Item;
use Modules\Moving\Entities\Room;

class ItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
           'name'           => $this->faker->word(),
           'description'    => $this->faker->text(),
           'cubic_feet'     => $this->faker->randomFloat(2, 1, 1000),
           'quantity'       => $this->faker->randomNumber(2),
           'cubic_feet'     => $this->faker->randomFloat(2, 1, 1000),
           'tag'            => $this->faker->randomElement(['ocean']),
           'active'         => $this->faker->randomElement([1]),
        ];
    }
}

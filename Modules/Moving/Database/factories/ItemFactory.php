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
           'name_pt'        => $this->faker->text(),
           'name_es'        => $this->faker->text(),
           'description'    => $this->faker->text(),
           'cubic_feet'     => $this->faker->randomFloat(2, 1, 1000),
           'packing_qty'    => $this->faker->randomNumber(2),
           'cubic_feet'     => $this->faker->randomFloat(2, 1, 1000),
           'tag'            => $this->faker->randomElement(['ocean']),
        ];
    }
}

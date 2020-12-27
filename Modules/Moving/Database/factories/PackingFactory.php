<?php

namespace Modules\Moving\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Moving\Entities\Packing;

class PackingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Packing::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        //return [
        //    'name'   => $this->faker->word(),
        //];
    }
}

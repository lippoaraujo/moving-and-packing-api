<?php

namespace Modules\System\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\System\Entities\Module;

class ModuleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Module::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $order = Module::all()->max('order');
        $order += 1;

        return [
            'name'          => $this->faker->word(),
            'description'   => $this->faker->paragraph(),
            'parent_module' => null,
            'order'         => $order,
            'color'         => $this->faker->colorName(),
            'image'         => $this->faker->imageUrl(),
            'active'        => $this->faker->randomElement([1]),
        ];
    }
}

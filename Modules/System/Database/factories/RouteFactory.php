<?php

namespace Modules\System\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\System\Entities\Module;
use Modules\System\Entities\Route;

class RouteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Route::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $module = Module::all()->first();

        return [
            'name'        => $this->faker->word(),
            'controllers' => "{$this->faker->word()}Controller",
            'module_id'   => $module->id,
            'active'      => $this->faker->randomElement([1]),
        ];
    }
}

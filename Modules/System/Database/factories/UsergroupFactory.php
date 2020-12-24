<?php

namespace Modules\System\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\System\Entities\Usergroup;
use Modules\System\Entities\Tenant;

class UsergroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Usergroup::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $tenant = Tenant::all()->first();

        return [
            'name'      => $this->faker->word(),
            'tenant_id' => $tenant->id,
            'active'    => $this->faker->randomElement([1]),
        ];
    }
}

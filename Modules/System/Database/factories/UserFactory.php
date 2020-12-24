<?php

namespace Modules\System\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\System\Entities\Tenant;
use Modules\System\Entities\User;
use Modules\System\Entities\Usergroup;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $userTenant = Tenant::first();
        $userGroup = Usergroup::first();

        return [
            'name'           => $this->faker->name(),
            'email'          => $this->faker->unique()->safeEmail,
            // 'email_verified_at' => now(),
            'password'       => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'usergroup_id'   => $userGroup->id,
            'tenant_id'      => $userTenant->id,
            'active'         => $this->faker->randomElement([1]),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that is a user confirmed password.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function passConfirmed()
    {
        return $this->state(function (array $attributes) {
            return [
                'password_confirmation' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            ];
        });
    }
}

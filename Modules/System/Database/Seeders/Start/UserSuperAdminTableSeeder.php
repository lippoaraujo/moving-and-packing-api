<?php

namespace Modules\System\Database\Seeders\Start;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use \Modules\System\Entities\User;

class UserSuperAdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $user = User::where('name', 'LippoAraujo')->first();

        //create Super Admin
        if (empty($user)) {

            $email = config('api.apiEmail');
            $pass  = config('api.apiPassword');

            $user = User::create([
                'name' => 'LippoAraujo',
                'tenant_id' => 1,
                'email' => $email,
                'password' => $pass,
            ]);

            $this->command->info(" ");
            $this->command->info("Super Admin was created: {$user->name}");
            $this->command->info("=========================");
            $this->command->info("E-mail: " . $user->email);
            $this->command->info("Pass: " . $pass);
            $this->command->info("=========================");
            $this->command->info(" ");
        } else {
            $this->command->warn("INFO: User {$user->name} alredy exist: {$user->email}");
        }
    }
}

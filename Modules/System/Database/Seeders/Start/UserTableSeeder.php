<?php

namespace Modules\System\Database\Seeders\Start;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use \Modules\System\Entities\User;

use Illuminate\Http\Response;
use Modules\System\Database\Seeders\Exceptions\SeedUserRoleNotFound;
use Spatie\Permission\Models\Role;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        //create user with Admin group Test
        $role = Role::where('name', 'Admin')->first();

        if (!empty($role)) {

            $user = User::where('name', 'UserTest')->first();

            if (empty($user)) {

                $email = 'usertest@gmail.com';
                $pass  = 'usertest@gmail.com';

                $user = User::create([
                    'name' => 'UserTest',
                    'email' => $email,
                    'password' => $pass,
                    'tenant_id' => 1,
                ]);

                $user->assignRole([$role->id]);

                $this->command->info(" ");
                $this->command->info("User Admin test was created: {$user->name}");
                $this->command->info("=========================");
                $this->command->info("E-mail: " . $user->email);
                $this->command->info("Pass: " . $pass);
                $this->command->info("=========================");
                $this->command->info(" ");
            } else {
                $this->command->warn("INFO: User {$user->name} alredy exist: {$user->email}");
            }
        } else {
            $message = 'ERROR: Role not found!';
            $this->command->error($message);
            throw new SeedUserRoleNotFound($message, Response::HTTP_NOT_FOUND);
        }
    }
}

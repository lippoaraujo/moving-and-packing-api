<?php

namespace Modules\Moving\Database\Seeders\Start;

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

        $role = Role::where('name', 'Seller')->first();

        if (!empty($role)) {

            $user = User::where('name', 'SellerTest')->first();

            if (empty($user)) {

                $email = 'sellertest@gmail.com';
                $pass  = 'sellertest@gmail.com';

                $user = User::create([
                    'name' => 'UserSeller',
                    'tenant_id' => 1,
                    'email' => $email,
                    'password' => $pass,
                ]);

                $this->command->info(" ");
                $this->command->info("User was created: {$user->name} with Role {$role->name}");
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

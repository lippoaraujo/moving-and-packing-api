<?php

namespace Modules\System\Database\Seeders\Start;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use \Modules\System\Entities\Usergroup;
use \Modules\System\Entities\User;

use Faker\Generator as Faker;
use Faker\Provider\Internet as FakeInternet;
use Illuminate\Http\Response;
use Modules\System\Database\Seeders\Exceptions\SeedUsergroupNotFound;

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

        $usergroup = Usergroup::where('name', 'master')->first();

        if (!empty($usergroup)) {

            $user = User::where('usergroup_id', $usergroup->id)->first();

            if (empty($user)) {

                $email = config('api.apiEmail');
                $pass  = config('api.apiPassword');

                $user = User::factory()->create([
                    'name' => 'admin',
                    'usergroup_id' => $usergroup->id,
                    'tenant_id' => $usergroup->tenant->id,
                    'email' => $email,
                    'password' => $pass,
                ]);

                $this->command->info(" ");
                $this->command->info("User was created: {$user->name} from group {$usergroup->name}");
                $this->command->info("=========================");
                $this->command->info("E-mail: " . $user->email);
                $this->command->info("Pass: " . $pass);
                $this->command->info("=========================");
                $this->command->info(" ");
            } else {
                $this->command->warn("INFO: User {$user->name} alredy exist: {$user->email}");
            }
        } else {
            $message = 'ERROR: Usergroup not found!';
            $this->command->error($message);
            throw new SeedUsergroupNotFound($message, Response::HTTP_NOT_FOUND);
        }
    }
}

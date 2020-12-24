<?php

namespace Modules\System\Database\Seeders\Start;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Modules\System\Database\Seeders\Exceptions\SeedTenantNotFound;
use \Modules\System\Entities\Usergroup;
use \Modules\System\Entities\Tenant;

class UsergroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $tenant = Tenant::first();

        if(!empty($tenant)) {
            $where = [
                'name'      => 'master',
                'tenant_id' => $tenant->id,
            ];
            $usergroup = Usergroup::where($where)->first();

            if(empty($usergroup)) {

                $usergroup = Usergroup::factory()->create([
                    'name' => 'master',
                    'tenant_id' => $tenant->id,
                ]);

                $this->command->info("INFO: usergroup was created: {$usergroup->name}");

            } else{
                $this->command->warn("INFO: usergroup alredy exist: {$usergroup->name}");
            }

        } else {
            $message = 'ERROR: Tenant not found!';
            $this->command->error($message);
            throw new SeedTenantNotFound($message, Response::HTTP_NOT_FOUND);
        }
    }
}

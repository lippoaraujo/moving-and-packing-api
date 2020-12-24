<?php

namespace Modules\System\Database\Seeders\Start;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use \Modules\System\Entities\Module;

class ModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $module = Module::where('name', 'system')->first();

        if (empty($module)) {

            $module = Module::factory()->create([
                'name' => 'system',
                'color' => '#1F7087',
                'image' => 'mdi-shield-home',
                'description' => 'users, permissions, system management...',
            ]);

            $this->command->info("INFO: Module was created: {$module->name}");

        } else {
            $this->command->warn("INFO: Module alredy exist: {$module->name}");
        }
    }
}

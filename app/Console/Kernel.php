<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Console\Commands\{
    FactoryMakeCommand,
    RepositoryContractMakeCommand,
    RepositoryMakeCommand,
    ServiceMakeCommand,
    ControllerMakeCommand,
    ModelMakeCommand,
    RequestMakeCommand
};

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        RepositoryMakeCommand::class,
        RepositoryContractMakeCommand::class,
        FactoryMakeCommand::class,
        ServiceMakeCommand::class,
        ControllerMakeCommand::class,
        RequestMakeCommand::class,
        ModelMakeCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\TraversePath::class,
        Commands\ImportMilleniumAlbums::class,
        Commands\GenerateAlbumSips::class,
        Commands\GenerateStandaloneSips::class,
        Commands\GeneratePDFSips::class,
        Commands\GenerateRootAcmsCSV::class,
        Commands\GenerateCSVSips::class,
        Commands\GenerateDigitArchiveSips::class,
        Commands\GenerateDigitArchiveSip::class,
        Commands\GenerateMilleniumSips::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}

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
        Commands\UpdateExchangeRates::class,
        Commands\AutoStopTimer::class,
        Commands\LicenceExpire::class,
        Commands\checkPaypalPlan::class,
        Commands\HideCoreJobMessage::class,
        Commands\SendProjectReminder::class,
        Commands\SetStorageLimitExistingCompanies::class,
        Commands\AutoCreateRecurringInvoices::class,
        Commands\FreeLicenceRenew::class,
        Commands\AutoCreateRecurringExpenses::class

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('update-exchange-rate')->daily();
        $schedule->command('auto-stop-timer')->daily();
        $schedule->command('licence-expire')->daily();
        $schedule->command('check-paypal-plan')->everyThirtyMinutes();
        $schedule->command('hide-cron-message')->everyMinute();
        $schedule->command('send-project-reminder')->daily();
        $schedule->command('free-licence-renew')->daily();
        $schedule->command('recurring-invoice-create')->daily();
        $schedule->command('recurring-expenses-create')->daily();
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

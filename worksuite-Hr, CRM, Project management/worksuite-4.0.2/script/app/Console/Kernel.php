<?php

namespace App\Console;

use App\Console\Commands\AddMenu;
use App\Console\Commands\AutoCreateRecurringExpenses;
use App\Console\Commands\AutoCreateRecurringInvoices;
use App\Console\Commands\AutoStopTimer;
use App\Console\Commands\CreateTranslations;
use App\Console\Commands\HideCoreJobMessage;
use App\Console\Commands\SendAutoTaskReminder;
use App\Console\Commands\SendEventReminder;
use App\Console\Commands\SendProjectReminder;
use App\Console\Commands\UpdateExchangeRates;
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
        UpdateExchangeRates::class,
        AutoStopTimer::class,
        SendEventReminder::class,
        SendProjectReminder::class,
        HideCoreJobMessage::class,
        SendAutoTaskReminder::class,
        CreateTranslations::class,
        AddMenu::class,
        AutoCreateRecurringInvoices::class,
        AutoCreateRecurringExpenses::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('update-exchange-rate')->daily();
        $schedule->command('auto-stop-timer')->daily();
        $schedule->command('send-event-reminder')->everyMinute();
        $schedule->command('send-project-reminder')->daily();
        $schedule->command('hide-cron-message')->everyMinute();
        $schedule->command('send-auto-task-reminder')->daily();
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
        // require base_path('routes/console.php');
    }

}

<?php

namespace App\Console\Commands;

use App\Setting;
use Carbon\Carbon;
use Illuminate\Console\Command;

class HideCoreJobMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hide-cron-message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hide crone job message.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $company = \App\Setting::first();

        $company->last_cron_run = Carbon::now();
        $company->save();

        $setting = Setting::first();
        $setting->hide_cron_message = 1;
        $setting->save();
    }
}

<?php

namespace App\Console\Commands;

use App\AttendanceSetting;
use App\Company;
use App\GlobalSetting;
use App\LogTimeFor;
use App\ProjectTimeLog;
use App\Setting;
use App\User;
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
        $globalSetting = GlobalSetting::first();
        $globalSetting->last_cron_run = Carbon::now();
        $globalSetting->save();

        $setting = GlobalSetting::first();
        $setting->hide_cron_message = 1;
        $setting->save();
    }
}

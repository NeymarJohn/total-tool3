<?php

namespace App\Console\Commands;

use App\AttendanceSetting;
use App\LogTimeFor;
use App\ProjectTimeLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoStopTimer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto-stop-timer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stop all employees timer after office time.';

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
       //      12:22:31
        $logTimeFor = LogTimeFor::first();
        $admin = User::allAdmins()->first();

        $attendanceSetting = AttendanceSetting::first();

        if ($logTimeFor->auto_timer_stop == 'yes') {
            $activeTimers = ProjectTimeLog::with('user')
                ->whereNull('end_time')
                ->join('users', 'users.id', '=', 'project_time_logs.user_id');

            $activeTimers = $activeTimers
                ->select('project_time_logs.*', 'users.name')
                ->get();

            foreach ($activeTimers as $activeTimer) {
                if (Carbon::createFromFormat('Y-m-d H:i:s', $activeTimer->start_time->format('Y-m-d').' '.$attendanceSetting->office_end_time)->timestamp < Carbon::now()->timestamp) {

                    $activeTimer->end_time = Carbon::now();
                    $activeTimer->edited_by_user = $admin->id;
                    $activeTimer->save();

                    $activeTimer->total_hours = ($activeTimer->end_time->diff($activeTimer->start_time)->format('%d') * 24) + ($activeTimer->end_time->diff($activeTimer->start_time)->format('%H'));

                    if ($activeTimer->total_hours == 0) {
                        $activeTimer->total_hours = round(($activeTimer->end_time->diff($activeTimer->start_time)->format('%i') / 60), 2);
                    }
                    $activeTimer->total_minutes = ($activeTimer->total_hours * 60) + ($activeTimer->end_time->diff($activeTimer->start_time)->format('%i'));

                    $activeTimer->save();
                }
            }
        }
    }
}

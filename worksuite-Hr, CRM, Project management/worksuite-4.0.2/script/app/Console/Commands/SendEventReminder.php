<?php

namespace App\Console\Commands;

use App\Event;
use App\Events\EventReminderEvent;
use App\Notifications\EventReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendEventReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-event-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send event reminder to the attendees before time specified in database';

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
        $events = Event::select('id', 'event_name', 'label_color', 'where', 'description', 'start_date_time', 'end_date_time', 'repeat', 'send_reminder', 'remind_time', 'remind_type')->where('start_date_time', '>=', Carbon::now(global_setting()->timezone))->where('send_reminder', 'yes')->get();

        if ($events->count() > 0) {
            foreach ($events as $event) {
                $reminderDateTime = $this->calculateReminderDateTime($event);
                // dd(Carbon::now(global_setting()->timezone)->startOfMinute());
                // dd($reminderDateTime);
                // dd(Carbon::now(global_setting()->timezone)->startOfMinute());
                if ($reminderDateTime->equalTo(Carbon::now(global_setting()->timezone)->startOfMinute())) {
                    $users = $event->getUsers();
                    // dd($reminderDateTime);
                    event(new EventReminderEvent($event));
                }
            }
        }
    }

    public function calculateReminderDateTime(Event $event)
    {
        $time = $event->remind_time;
        $type = $event->remind_type;

        $reminderDateTime = '';

        switch ($type) {
            case 'day':
                $reminderDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $event->start_date_time, global_setting()->timezone)->subDays($time);
                break;
            case 'hour':
                $reminderDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $event->start_date_time, global_setting()->timezone)->subHours($time);
                break;
            case 'minute':
                $reminderDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $event->start_date_time, global_setting()->timezone)->subMinutes($time);
                break;
        }

        return $reminderDateTime;
    }
}

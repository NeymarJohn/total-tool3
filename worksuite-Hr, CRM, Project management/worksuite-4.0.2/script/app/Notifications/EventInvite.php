<?php

namespace App\Notifications;

use App\Event;
use App\Setting;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EventInvite extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $event;
    private $global;
    public function __construct(Event $event)
    {
        $this->event = $event;

        $this->global = cache()->remember(
            'global-setting', 60*60*24, function () {
                return \App\Setting::first();
            }
        );
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $via = ['database'];
        if ($notifiable->email_notifications) {
            array_push($via, 'mail');
        }
        return $via;
    }

    /**
     * @param $notifiable
     * @return MailMessage
     * @throws \Exception
     */
    public function toMail($notifiable)
    {
        $vCalendar = new \Eluceo\iCal\Component\Calendar('www.example.com');
        $vEvent = new \Eluceo\iCal\Component\Event();
        $vEvent
            ->setDtStart(new \DateTime($this->event->start_date_time))
            ->setDtEnd(new \DateTime($this->event->end_date_time))
            ->setNoTime(true)
            ->setSummary(ucfirst($this->event->event_name));
        $vCalendar->addComponent($vEvent);
        $vFile = $vCalendar->render();
        return (new MailMessage)
            ->subject(__('email.newEvent.subject') . ' - ' . config('app.name'))
            ->greeting(__('email.hello') . ' ' . ucwords($notifiable->name) . '!')
            ->line(__('email.newEvent.text'))
            ->line(__('modules.events.eventName') .': '. $this->event->event_name)
            ->line(__('modules.events.startOn') .': '. $this->event->start_date_time->format($this->global->date_format. ' - '.$this->global->time_format))
            ->line(__('modules.events.endOn') .': '. $this->event->end_date_time->format($this->global->date_format. ' - '.$this->global->time_format))
            ->action(__('email.loginDashboard'), url('/'))
            ->line(__('email.thankyouNote'))
            ->attachData($vFile, 'cal.ics', [
                'mime' => 'text/calendar',
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->event->id,
            'start_date_time' => $this->event->start_date_time->format('Y-m-d H:i:s'),
            'event_name' => $this->event->event_name
        ];
    }
}

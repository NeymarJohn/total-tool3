<?php

namespace App\Notifications;

use App\ProjectTimeLog;
use Illuminate\Notifications\Messages\MailMessage;

class TimerStarted extends BaseNotification
{


    /**
     * Create a new notification instance.
     *
     * @return void
     */

    private $timeLog;
    public function __construct(ProjectTimeLog $projectTimeLog)
    {
        parent::__construct();
        $this->timeLog = $projectTimeLog;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line(__('email.timerStart.text'))
            ->action(__('email.timerStart.notificationAction'), url('/'))
            ->line(__('email.thankyouNote'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->timeLog->toArray();
    }
}

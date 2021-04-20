<?php

namespace App\Notifications;


use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class ProjectReminder extends Notification implements ShouldQueue
{
    use Queueable;

    private $projects;
    private $data;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($projects, $data)
    {
        $this->projects = $projects;
        $this->data = $data;

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $via = array();
        if ($notifiable->email_notifications) {
            array_push($via, 'mail');
        }
        return $via;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject(__('email.projectReminder.subject') . ' - ' . config('app.name'))
            ->greeting(__('email.hello') . ' ' . ucwords($notifiable->name) . '!')
            ->line(__('email.projectReminder.text') . ' ' . Carbon::now($this->data['global_setting']->timezone)->addDays($this->data['project_setting']->remind_time)->toFormattedDateString());

        $list = '<ol>';
        foreach ($this->projects as $project) {
            $list .= '<li>' . $project->project_name . '</li>';
        }
        $list .= '</ol>';

        return $message
            ->line(new HtmlString($list))
            ->line(__('email.messages.loginForMoreDetails'))
            ->action(__('email.loginDashboard'), url('/'))
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
        return $this->projects->toArray();
    }
}

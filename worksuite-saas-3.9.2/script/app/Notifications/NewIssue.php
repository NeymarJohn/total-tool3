<?php

namespace App\Notifications;

use App\Issue;
use Illuminate\Notifications\Messages\MailMessage;

class NewIssue extends BaseNotification
{


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $issue;
    public function __construct(Issue $issue)
    {
        parent::__construct();
        $this->issue = $issue;
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
            ->line(__('email.issue.text'))
            ->action(__('email.issue.notificationAction'), url('/'))
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
        return $this->issue->toArray();
    }
}

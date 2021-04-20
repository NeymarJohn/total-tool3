<?php

namespace App\Notifications;

use App\Traits\SmtpSettings;
use App\UserChat;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewChat extends BaseNotification
{


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $userChat;
    public function __construct(UserChat $userChat)
    {
        parent::__construct();
        $this->userChat = $userChat;
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
            ->line(__('email.chat.text'))
            ->action(__('email.chat.notificationAction'), url('/'))
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
        return $this->userChat->toArray();
    }
}

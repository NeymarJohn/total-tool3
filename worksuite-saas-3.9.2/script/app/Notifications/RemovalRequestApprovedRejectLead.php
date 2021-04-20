<?php

namespace App\Notifications;
use Illuminate\Notifications\Messages\MailMessage;

class RemovalRequestApprovedRejectLead extends BaseNotification
{


    protected $type;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($type)
    {
        parent::__construct();
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
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
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if ($this->type == 'approved') {
            return (new MailMessage)
                ->subject(__('email.removalRequestApprovedLead.subject') . ' ' . config('app.name') . '!')
                ->greeting(__('email.hello') . ' ' . ucwords($notifiable->client_name) . '!')
                ->line(__('email.removalRequestApprovedLead.text'))
                ->line(__('email.thankyouNote'));
        } else {
            return (new MailMessage)
                ->subject(__('email.removalRequestRejectedLead.subject') . ' ' . config('app.name') . '!')
                ->greeting(__('email.hello') . ' ' . ucwords($notifiable->client_name) . '!')
                ->line(__('email.removalRequestRejectedLead.text'))
                ->line(__('email.thankyouNote'));
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

<?php

namespace App\Notifications;

use App\Contract;
use Illuminate\Bus\Queueable;
use App\Traits\SmtpSettings;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewContract extends BaseNotification
{


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $contract;
    public function __construct(Contract $contract)
    {
        parent::__construct();
        $this->contract = $contract;
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
        if ($notifiable->email_notifications && $notifiable->client_detail->email_notifications) {
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

        $url = route('front.contract.show', md5($this->contract->id));

        return (new MailMessage)
            ->subject(__('email.contract.subject'))
            ->greeting(__('email.hello') . ' ' . ucwords($notifiable->name) . '!')
            ->line(__('email.contract.text'))
            ->action(__('email.contract.viewContract'), getDomainSpecificUrl($url, $notifiable->company))
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
        return $this->contract->toArray();
    }
}

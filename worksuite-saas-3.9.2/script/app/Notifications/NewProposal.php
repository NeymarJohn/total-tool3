<?php

namespace App\Notifications;

use App\Proposal;
use Illuminate\Notifications\Messages\MailMessage;

class NewProposal extends BaseNotification
{


    /**
     * Create a new notification instance.
     *
     * @return void
     */

    private $proposal;
    public function __construct(Proposal $proposal)
    {
        parent::__construct();
        $this->proposal = $proposal;
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
        $url = '';

        return (new MailMessage)
            ->subject(__('email.proposal.subject'))
            ->greeting(__('email.hello') . ' ' . ucwords($this->user->name) . '!')
            ->line(__('email.proposal.text'))
            ->action(__('email.loginDashboard'), getDomainSpecificUrl(url('/login'), $notifiable->company))
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
        return $this->proposal->toArray();
    }
}

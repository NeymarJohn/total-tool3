<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\Lead;

use App\Traits\SmtpSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class LeadAgentAssigned extends BaseNotification
{


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $lead;
    public function __construct(Lead $lead)
    {
        parent::__construct();
        $this->lead = $lead;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $user = $notifiable;
        $url = url('/');

        return (new MailMessage)
            ->subject(__('email.leadAgent.subject') . ' - ' . config('app.name'))
            ->greeting(__('email.hello') . ' ' . ucwords($user->name) . '!')
            ->line(__('email.leadAgent.subject'))
            ->line(__('modules.lead.leadDetails') . ':- ')
            ->line(__('modules.lead.clientName') . ': ' . $this->lead->client_name)
            ->line(__('modules.lead.clientEmail') . ': ' . $this->lead->client_email)
            ->action(__('email.loginDashboard'), $url)
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
        return $this->lead->toArray();
    }
}

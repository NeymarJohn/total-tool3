<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\PushNotificationSetting;
use App\TicketReply;
use Illuminate\Notifications\Messages\MailMessage;
use App\SlackSetting;
use Illuminate\Notifications\Messages\SlackMessage;

class NewTicketReply extends BaseNotification
{


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $ticket;
    public function __construct(TicketReply $ticket)
    {
        parent::__construct();
        $this->emailSetting = EmailNotificationSetting::where('setting_name', 'New Support Ticket Request')->first();
        $this->ticket = $ticket->ticket;
        $this->pushNotification = PushNotificationSetting::first();
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

        if ($this->emailSetting->send_email == 'yes' && $notifiable->email_notifications) {
            array_push($via, 'mail');
        }

        if ($this->emailSetting->send_slack == 'yes') {
            array_push($via, 'slack');
        }

        return $via;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__('email.ticketReply.subject') . ' - ' . ucfirst($this->ticket->subject))
            ->greeting(__('email.hello') . ' ' . ucwords($notifiable->name) . '!')
            ->line(__('email.ticketReply.text') . ' # ' . $this->ticket->id)
            ->action(__('email.loginDashboard'), url('/'))
            ->line(__('email.thankyouNote'));
    }

    public function toSlack($notifiable)
    {
        if ($notifiable->isEmployee) {
            $slack = SlackSetting::first();
            if (count($notifiable->employee) > 0 && (!is_null($notifiable->employee[0]->slack_username) && ($notifiable->employee[0]->slack_username != ''))) {
                return (new SlackMessage())
                    ->from(config('app.name'))
                    ->image($slack->slack_logo_url)
                    ->to('@' . $notifiable->employee[0]->slack_username)
                    ->content('*' . __('email.ticketReply.subject') . '*' . "\n" . ucfirst($this->ticket->subject) . "\n" . __('modules.tickets.requesterName') . ' - ' . ucwords($this->ticket->requester->name));
            }
            return (new SlackMessage())
                ->from(config('app.name'))
                ->image($slack->slack_logo_url)
                ->content('This is a redirected notification. Add slack username for *' . ucwords($notifiable->name) . '*');
        }
    }


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->ticket->toArray();
    }
}

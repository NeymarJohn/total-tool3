<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\PushNotificationSetting;
use App\SlackSetting;
use App\TicketReply;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;
use NotificationChannels\OneSignal\OneSignalMessage;

class ReplyTicket extends BaseNotification
{


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $ticketReply;
    private $emailSetting;

    public function __construct(TicketReply $ticketReply)
    {
        parent::__construct();
        $this->ticketReply = $ticketReply->load('ticket');

        $this->emailSetting = EmailNotificationSetting::where('setting_name', 'New Support Ticket Request')->first();
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

        //        if($this->emailSetting->send_slack == 'yes'){
        //            array_push($via, 'slack');
        //        }
        //
        //        if($this->emailSetting->send_push == 'yes' && $this->pushNotification->status == 'active'){
        //            array_push($via, OneSignalChannel::class);
        //        }

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
        return (new MailMessage)
            ->subject(__('email.ticketReply.subject') . ' - ' . config('app.name'))
            ->greeting(__('email.hello') . ' ' . ucwords($notifiable->name) . '!')
            ->line(new HtmlString(__('email.ticketReply.text', ['subject' => '<strong>' . $this->ticketReply->ticket->subject . '</strong>'])))
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
        return $this->ticketReply->toArray();
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $slack = SlackSetting::first();
        if (count($notifiable->employee) > 0 && (!is_null($notifiable->employee[0]->slack_username) && ($notifiable->employee[0]->slack_username != ''))) {
            return (new SlackMessage())
                ->from(config('app.name'))
                ->image(asset('storage/slack-logo/' . $slack->slack_logo))
                //                ->to('@abhinav')
                ->to('@' . $notifiable->employee[0]->slack_username)
                ->content('*' . __('email.newTicket.subject') . '*' . "\n" . ucfirst($this->ticket->subject) . "\n" . __('modules.tickets.requesterName') . ' - ' . ucwords($this->ticket->requester->name));
        }
        return (new SlackMessage())
            ->from(config('app.name'))
            ->image($slack->slack_logo_url)
            ->content('This is a redirected notification. Add slack username for *' . ucwords($notifiable->name) . '*');
    }

    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->subject(__('email.newTicket.subject'))
            ->body(__('email.newTicket.text'));
    }
}

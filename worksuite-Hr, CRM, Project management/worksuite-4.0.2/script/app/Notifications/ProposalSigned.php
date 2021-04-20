<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\Proposal;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class ProposalSigned extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $proposal;
    private $emailSetting;

    public function __construct(Proposal $proposal)
    {
        $this->proposal = $proposal;
        $this->emailSetting = EmailNotificationSetting::where('setting_name', 'Lead notification')->first();

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
        if ($notifiable->email_notifications && $this->emailSetting->send_email == 'yes') {
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
        $user = $notifiable;
        $url = '';
        if($this->proposal->status == 'accepted'){
            return (new MailMessage)
                ->subject(__('email.proposalSigned.subject'))
                ->greeting(__('email.hello') . ' ' . ucwords($user->name) . '!')
                ->line(__('email.proposalSigned.approve') . ':- ')
                ->line(__('app.status') . ': ' . ucwords($this->proposal->status))
                ->action('Login To Dashboard', $url)
                ->line(__('email.thankyouNote'));
        }
        return (new MailMessage)
            ->subject(__('email.proposalRejected.subject'))
            ->greeting(__('email.hello') . ' ' . ucwords($user->name) . '!')
            ->line(__('email.proposalRejected.rejected') . ':- ')
            ->line(__('app.status') . ': ' . ucwords($this->proposal->status))
            ->action('Login To Dashboard', $url)
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

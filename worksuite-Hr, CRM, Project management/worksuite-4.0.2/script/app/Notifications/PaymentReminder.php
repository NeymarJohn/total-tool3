<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\Invoice;
use App\SlackSetting;
use App\Task;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class PaymentReminder extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $invoice;
    private $user;
    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
        $invoiceClientId = 0;
        if($invoice->project_id != null && $invoice->project_id != ''){
            $this->user = $invoice->project->client;
        }
        elseif($invoice->client_id != null && $invoice->client_id != ''){
            $this->user = $invoice->client;
        }
        $this->emailSetting = EmailNotificationSetting::where('slug', 'invoice-createupdate-notification')->first();

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

        if ($this->emailSetting->send_slack == 'yes') {
            array_push($via, 'slack');
        }

        if ($this->emailSetting->send_push == 'yes') {
            array_push($via, OneSignalChannel::class);
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
        $url = url('/login');
        $paymentUrl = route('front.invoice', [md5($this->invoice->id)]);

        $content = __('email.paymentReminder.subject') . ' ' . __('app.invoice') . ' ' . ucfirst($this->invoice->invoice_number) . '<p>
            <b style="color: green">' . __('app.dueDate') . ': ' . $this->invoice->due_date->format('d M, Y') . '</b>
        </p>';

        return (new MailMessage)
            ->subject(__('email.paymentReminder.subject') . ' - ' . config('app.name') . '!')
            ->greeting(__('email.hello') . ' ' . ucwords($this->user->name) . '!')
            ->markdown('mail.payment.reminder', ['url' => $url, 'paymentUrl' => $paymentUrl, 'content' => $content]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->invoice->id,
            'created_at' => $this->invoice->created_at->format('Y-m-d H:i:s'),
            'heading' => $this->invoice->invoice_number
        ];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $slack = SlackSetting::setting();
        if (count($notifiable->employee) > 0 && (!is_null($notifiable->employee[0]->slack_username) && ($notifiable->employee[0]->slack_username != ''))) {
            return (new SlackMessage())
                ->from(config('app.name'))
                ->image($slack->slack_logo_url)
                ->to('@' . $notifiable->employee[0]->slack_username)
                ->content('*' . __('email.paymentReminder.subject') . '*' . "\n" . __('app.invoice') . ' - ' . ucfirst($this->invoice->invoice_number));
        }
        return (new SlackMessage())
            ->from(config('app.name'))
            ->image($slack->slack_logo_url)
            ->content('This is a redirected notification. Add slack username for *' . ucwords($notifiable->name) . '*');
    }

    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->subject(__('email.paymentReminder.subject'))
            ->body($this->invoice->heading);
    }
}

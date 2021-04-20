<?php

namespace App\Notifications;

use App\Invoice;
use App\SlackSetting;
use App\User;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalMessage;

class PaymentReminder extends BaseNotification
{

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $invoice;
    private $user;

    public function __construct(Invoice $invoice)
    {
        parent::__construct();
        $this->invoice = $invoice;
        $this->user = User::findOrFail($invoice->project ? $invoice->project->client_id : $invoice->client_id);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $via = ['mail'];
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
        $url = url('/login');
        $paymentUrl = route('front.invoice', [md5($this->invoice->id)]);
        if ($this->invoice->project) {
            $content = 'Payment for ' . ucfirst($this->invoice->project->project_name) . ' invoice no. ' . ucfirst($this->invoice->invoice_number) . '<p>
            <b style="color: green">' . __('app.dueOn') . ' : ' . $this->invoice->due_date->format('d M, Y') . '</b>
        </p>';
        } else {
            $content = 'Payment for invoice no. ' . ucfirst($this->invoice->invoice_number) . '<p>
            <b style="color: green">' . __('app.dueOn') . ' : ' . $this->invoice->due_date->format('d M, Y') . '</b>
        </p>';
        }
        return (new MailMessage)
            ->subject(__('email.paymentReminder.subject') . ' - ' . config('app.name') . '!')
            ->greeting(__('email.hello') . ' ' . ucwords($this->user->name) . '!')
            ->markdown('mail.payment.reminder', ['url' => getDomainSpecificUrl($url, $notifiable->company), 'paymentUrl' => $paymentUrl, 'content' => $content]);
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
            'id' => $this->invoice->id,
            'created_at' => $this->invoice->created_at->format('Y-m-d H:i:s'),
            'heading' => $this->invoice->invoice_number
        ];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param mixed $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $slack = SlackSetting::first();
        if (count($notifiable->employee) > 0 && (!is_null($notifiable->employee[0]->slack_username) && ($notifiable->employee[0]->slack_username != ''))) {
            return (new SlackMessage())
                ->from(config('app.name'))
                ->image($slack->slack_logo_url)
                ->to('@' . $notifiable->employee[0]->slack_username)
                ->content(__('email.paymentReminder.subject'));
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

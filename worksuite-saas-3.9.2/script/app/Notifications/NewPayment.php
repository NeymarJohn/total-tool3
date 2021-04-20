<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\Payment;
use App\PushNotificationSetting;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;

class NewPayment extends BaseNotification
{


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $payment;
    private $emailSetting;

    public function __construct(Payment $payment)
    {
        parent::__construct();
        $this->payment = $payment;
        $this->emailSetting = EmailNotificationSetting::where('setting_name', 'Payment Create/Update Notification')->first();
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
        $via = [];

        if ($this->emailSetting->send_email == 'yes' && $notifiable->email_notifications) {
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
        //        $url = route('client.payments.index');

        if (($this->payment->project_id && $this->payment->project->client_id != null) || ($this->payment->invoice_id && $this->payment->invoice->client_id != null)) {
            $url = route('front.invoice', md5($this->payment->invoice_id));
            return (new MailMessage)
                ->subject(__('email.payment.subject'))
                ->greeting(__('email.hello') . ' ' . ucwords($notifiable->name) . '!')
                ->line(__('email.payment.text'))
                ->action(__('email.invoice.viewInvoice'), getDomainSpecificUrl($url, $notifiable->company))
                ->line(__('email.thankyouNote'));
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
        return $this->payment->toArray();
    }
}

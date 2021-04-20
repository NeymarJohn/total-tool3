<?php

namespace App\Notifications;

use App\InvoiceSetting;
use App\OfflineInvoicePayment;
use Illuminate\Notifications\Messages\MailMessage;

class OfflineInvoicePaymentReject extends BaseNotification
{


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $payment;
    private $invoiceSetting;
    public function __construct(OfflineInvoicePayment $paymentRequest)
    {
        parent::__construct();
        $this->payment = $paymentRequest;
        $this->invoiceSetting = InvoiceSetting::first();
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
        if ($notifiable->email_notifications) {
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
        return (new MailMessage)
            ->subject(__('email.offlinePaymentReject.subject'))
            ->greeting(__('email.hello') . '!')
            ->line(__('email.offlinePaymentReject.text', ['invoice_id' => $this->invoiceSetting->invoice_prefix . ' #' . $this->payment->invoice->id]))
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
        return $this->payment->toArray();
    }
}

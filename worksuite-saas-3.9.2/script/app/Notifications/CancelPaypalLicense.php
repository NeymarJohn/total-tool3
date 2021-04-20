<?php

namespace App\Notifications;

use App\Company;
use App\PaypalInvoice;
use Illuminate\Notifications\Messages\MailMessage;

class CancelPaypalLicense extends BaseNotification
{

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Company $company, $invoiceId)
    {
        parent::__construct();
        $this->company = $company;
        $this->paypalInvoice = PaypalInvoice::findOrFail($invoiceId);
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
        $link = ($notifiable->superadmin == 1) ? getDomainSpecificUrl(url('/login')) : getDomainSpecificUrl(url('/login'), $this->company);
        return (new MailMessage)
            ->subject(__('email.cancelLicense.subject') . ' ' . config('app.name') . '!')
            ->greeting(__('email.hello') . ' ' . ucwords($notifiable->name) . '!')
            ->line(__('email.cancelLicense.text'))
            ->line(__('modules.accountSettings.companyName') . ': ' . $this->company->company_name)
            ->line(__('modules.payments.paidOn') . ': ' . $this->paypalInvoice->paid_on->format('d M, Y') . ' (PayPal)')
            ->action(__('email.loginDashboard'), $link)
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
        return [
            //
        ];
    }
}

<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\PushNotificationSetting;
use App\RecurringInvoice;
use Illuminate\Notifications\Messages\MailMessage;

class NewRecurringInvoice extends BaseNotification
{


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $invoice;
    private $emailSetting;

    public function __construct(RecurringInvoice $invoice)
    {
        parent::__construct();
        $this->invoice = $invoice;
        $this->emailSetting = EmailNotificationSetting::where('setting_name', 'Invoice Create/Update Notification')->first();
        $this->pushNotification = PushNotificationSetting::first();

        $this->company_id = company()->id;
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

        if ($this->emailSetting->send_email == 'yes' && $notifiable->email_notifications && $notifiable->client_detail->email_notifications) {
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
        $url = url('/');

        return (new MailMessage)
            ->subject(__('email.newInvoiceRecurring.subject'))
            ->greeting(__('email.hello') . ' ' . ucwords($notifiable->name) . '!')
            ->line(__('email.newInvoiceRecurring.subject') . '.')
            ->line(__('email.newInvoiceRecurring.text'))
            ->line(__('app.total') . ': ' . $this->invoice->currency->currency_symbol . $this->invoice->total)
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
        if (!is_null($this->invoice->project_id)) {
            return [
                'id' => $this->invoice->id,
                'invoice_number' => $this->invoice->invoice_number,
                'project_name' => $this->invoice->project->project_name,
            ];
        } else {
            return [
                'id' => $this->invoice->id,
                'invoice_number' => $this->invoice->invoice_number,
            ];
        }
        return $this->invoice->toArray();
    }
}

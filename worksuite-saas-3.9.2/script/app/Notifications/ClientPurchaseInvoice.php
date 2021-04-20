<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\Http\Controllers\Admin\ManageAllInvoicesController;
use App\Invoice;
use App\PushNotificationSetting;
use App\Scopes\CompanyScope;
use App\Traits\SmtpSettings;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
use App\User;
use Illuminate\Support\Facades\Config;

class ClientPurchaseInvoice extends BaseNotification
{


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $invoice;
    private $emailSetting;

    public function __construct(Invoice $invoice)
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
        $user = $notifiable;
        $url = url('/');

        return (new MailMessage)
            ->subject(__('email.productPurchase.subject') . ' - ' . config('app.name'))
            ->greeting(__('email.hello') . ' ' . ucwords($user->name) . '!')
            ->line(__('email.productPurchase.subject'))
            ->line(__('email.productPurchase.text') . ' ' . ucwords($this->invoice->client->name) . '.')
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

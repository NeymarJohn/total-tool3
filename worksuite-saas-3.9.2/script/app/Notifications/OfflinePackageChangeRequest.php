<?php

namespace App\Notifications;

use App\OfflinePlanChange;
use Illuminate\Notifications\Messages\MailMessage;

class OfflinePackageChangeRequest extends BaseNotification
{


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $planChange;
    private $company;
    public function __construct($company, OfflinePlanChange $planChange)
    {
        parent::__construct();
        $this->planChange = $planChange;
        $this->company = $company;
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
            ->subject(__('email.offlinePackageChangeRequest.subject'))
            ->greeting(__('email.hello') . ' ' . ucwords($notifiable->name) . '!')
            ->line(__('email.offlinePackageChangeRequest.text', ['company' => $this->company->company_name]))
            ->line(__('email.offlinePackageChangeRequest.packageName') . ': ' . $this->planChange->package->name . ' (' . $this->planChange->package_type . ').')
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
        return $this->planChange->toArray();
        return $this->company->toArray();
    }
}

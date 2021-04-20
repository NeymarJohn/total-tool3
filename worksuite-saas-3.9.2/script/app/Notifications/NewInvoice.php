<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\Http\Controllers\Admin\ManageAllInvoicesController;
use App\Invoice;
use App\PushNotificationSetting;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;

class NewInvoice extends BaseNotification
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
        $url = route('front.invoice', md5($this->invoice->id));

        if (($this->invoice->project && !is_null($this->invoice->project->client)) || !is_null($this->invoice->client_id)) {
            // For Sending pdf to email
            $invoiceController = new ManageAllInvoicesController();
            $pdfOption = $invoiceController->domPdfObjectForDownload($this->invoice->id);
            $pdf = $pdfOption['pdf'];
            $filename = $pdfOption['fileName'];

            Config::set('app.name', $this->invoice->company->company_name);

            return (new MailMessage)
                ->subject(__('email.invoice.subject'))
                ->greeting(__('email.hello') . ' ' . ucwords($notifiable->name) . '!')
                ->line(__('email.invoice.text'))
                ->action(__('email.invoice.viewInvoice'), getDomainSpecificUrl($url, $notifiable->company))
                ->line(__('email.thankyouNote'))
                ->attachData($pdf->output(), $filename . '.pdf');
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

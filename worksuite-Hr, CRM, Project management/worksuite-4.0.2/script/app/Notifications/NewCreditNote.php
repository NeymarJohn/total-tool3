<?php

namespace App\Notifications;

use App\CreditNotes;
use App\EmailNotificationSetting;
use App\Http\Controllers\Admin\ManageAllCreditNotesController;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
class NewCreditNote extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $creditNotes;
    private $emailSetting;

    public function __construct(CreditNotes $creditNotes)
    {
        $this->creditNotes = $creditNotes;

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
        $via = $notifiable->email_notifications ? ['mail', 'database'] : ['database'];

        // if ($this->emailSetting[10]->send_slack == 'yes') {
        //     array_push($via, 'slack');
        // }

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
        $url = route('login');

        if ( !is_null($this->creditNotes->client_id)) {
            // For Sending pdf to email
            $invoiceController = new ManageAllCreditNotesController();
            if ($pdfOption = $invoiceController->domPdfObjectForDownload($this->creditNotes->id)) {
                $pdf = $pdfOption['pdf'];
                $filename = $pdfOption['fileName'];

                return (new MailMessage)
                    ->subject(__('email.creditNote.subject') . ' - ' . config('app.name') . '!')
                    ->greeting(__('email.hello') . ' ' . $notifiable->name . '!')
                    ->line(__('email.creditNote.text'))
                    ->action(__('email.loginDashboard'), $url)
                    ->line(__('email.thankyouNote'))
                    ->attachData($pdf->output(), $filename . '.pdf');
            }
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
        return [
            'id' => $this->creditNotes->id,
            'cn_number' => $this->creditNotes->cn_number
        ];
    }


}

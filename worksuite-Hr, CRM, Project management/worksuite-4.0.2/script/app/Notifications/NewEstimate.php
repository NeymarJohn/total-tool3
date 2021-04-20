<?php

namespace App\Notifications;

use App\Estimate;
use App\Http\Controllers\Admin\ManageEstimatesController;
use Illuminate\Bus\Queueable;

use App\User;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewEstimate extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $estimate;
    private $user;
    public function __construct(Estimate $estimate)
    {
        $this->estimate = $estimate;
        $this->user = User::findOrFail($estimate->client_id);

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
        $url = url(route('front.estimate.show', md5($this->estimate->id)));

        // For Sending pdf to email
        // $invoiceController = new ManageEstimatesController();
        // $pdfOption = $invoiceController->domPdfObjectForDownload($this->estimate->id);
        // $pdf = $pdfOption['pdf'];
        // $filename = $pdfOption['fileName'];

        return (new MailMessage)
            ->subject(__('email.estimate.subject').' - '.config('app.name').'!')
            ->greeting(__('email.hello').' '.ucwords($this->user->name).'!')
            ->line(__('email.estimate.text'))
            ->action(__('email.estimate.loginDashboard'), $url)
            ->line(__('email.thankyouNote'));
            // ->attachData($pdf->output(), $filename.'.pdf');
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
            'id' => $this->estimate->id,
            'estimate_number' => $this->estimate->estimate_number
        ];
    }
}

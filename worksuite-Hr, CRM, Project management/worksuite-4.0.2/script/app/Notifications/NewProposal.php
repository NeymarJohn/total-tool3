<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\Http\Controllers\Admin\ProposalController;
use App\Proposal;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewProposal extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    private $proposal;
    private $emailSetting;
    public function __construct(Proposal $proposal)
    {
        $this->proposal = $proposal;
        $this->emailSetting = EmailNotificationSetting::where('slug', 'lead-notification')->first();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if($this->emailSetting->send_email == 'yes'){
            return  ['mail'];
        }
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $proposalController = new ProposalController();
        if ($pdfOption = $proposalController->domPdfObjectForDownload($this->proposal->id)) {
            $pdf = $pdfOption['pdf'];
            $filename = $pdfOption['fileName'];

            return (new MailMessage)
                ->subject('New Lead Proposal Sent!')
                ->greeting('Hello ' . ucwords($this->proposal->lead->client_name) . '!')
                ->line('A new lead proposal has been sent to you.')
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
        return $this->proposal->toArray();
    }
}

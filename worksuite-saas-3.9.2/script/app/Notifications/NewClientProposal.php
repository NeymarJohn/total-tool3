<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\Http\Controllers\Front\HomeController;
use App\Proposal;
use Illuminate\Notifications\Messages\MailMessage;

class NewClientProposal extends BaseNotification
{

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    private $proposal;
    private $emailSetting;
    public function __construct(Proposal $proposal)
    {
        parent::__construct();
        $this->proposal = $proposal;
        $this->emailSetting = EmailNotificationSetting::where('setting_name', 'Lead notification')->first();

    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        if (!is_null($this->emailSetting) && $this->emailSetting->send_email == 'yes') {
            return ['mail'];
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
        $url = route('front.proposal', md5($this->proposal->id));

        $invoiceController = new HomeController();
        $pdfOption = $invoiceController->domPdfObjectProposalDownload($this->proposal->id);
        $pdf = $pdfOption['pdf'];
        $filename = $pdfOption['fileName'];

        return (new MailMessage)
            ->subject(__('email.clientProposal.subject'))
            ->greeting(__('email.hello') . ' ' . ucwords($this->proposal->lead->client_name) . '!')
            ->line(__('email.clientProposal.text'))
            ->action(__('email.clientProposal.viewProposal'), getDomainSpecificUrl($url, $this->proposal->company))
            ->line(__('email.thankyouNote'))
            ->attachData($pdf->output(), $filename . '.pdf');
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

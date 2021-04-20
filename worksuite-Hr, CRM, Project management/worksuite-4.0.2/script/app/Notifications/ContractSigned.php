<?php

namespace App\Notifications;

use App\Contract;
use App\ContractSign;
use App\Http\Controllers\Admin\ManageAllInvoicesController;
use App\Http\Controllers\PublicUrlController;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\HtmlString;

class ContractSigned extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $contract;
    private $contractSign;
    public function __construct(Contract $contract, ContractSign $contractSign)
    {
        $this->contract = $contract;
        $this->contractSign = $contractSign;
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

        $publicUrlController = new PublicUrlController();
        $pdfOption = $publicUrlController->contractDownloadView($this->contract->id);
        $pdf = $pdfOption['pdf'];
        $filename = $pdfOption['fileName'];

        return (new MailMessage)
            ->subject(__('email.contractSign.subject'))
            ->greeting(__('email.hello').' '.ucwords($notifiable->name).'!')
            ->line(new HtmlString(__('email.contractSign.text', ['contract' => '<strong>'.$this->contract->subject.'</strong>','client' => '<strong>'.$this->contract->client->name.'</strong>'])))
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
        return $this->contract->toArray();
    }
}

<?php

namespace App\Notifications;

use App\Contract;
use App\PushNotificationSetting;
use Illuminate\Notifications\Messages\SlackMessage;

class ContractComment extends BaseNotification
{


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $contract;
    private $created_at;
    public function __construct(Contract $contract, $created_at)
    {
        parent::__construct();
        $this->contract = $contract;
        $this->created_at = $created_at;
        $this->pushNotification = PushNotificationSetting::first();
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
        //
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
            'id' => $this->contract->id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'subject' => $this->contract->subject
            //            'completed_on' => $this->task->completed_on->format('Y-m-d H:i:s')
        ];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        //
    }

    public function toOneSignal($notifiable)
    {
        //
    }
}

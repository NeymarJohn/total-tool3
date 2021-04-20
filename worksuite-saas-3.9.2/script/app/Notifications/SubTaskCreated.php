<?php

namespace App\Notifications;

use App\PushNotificationSetting;
use App\SubTask;

class SubTaskCreated extends BaseNotification
{


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $subTask;
    public function __construct(SubTask $subTask)
    {
        parent::__construct();
        $this->subTask = $subTask;
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

        // if ($this->emailSetting->send_email == 'yes' && $notifiable->email_notifications) {
        //     array_push($via, 'mail');
        // }

        // if ($this->emailSetting->send_slack == 'yes') {
        //     array_push($via, 'slack');
        // }

        // if ($this->emailSetting->send_push == 'yes' && $this->pushNotification->status == 'active') {
        //     array_push($via, OneSignalChannel::class);
        // }

        return $via;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //         ->subject(__('email.subTaskComplete.subject') . ' - ' . config('app.name') . '!')
    //         ->greeting(__('email.hello') . ' ' . ucwords($notifiable->name) . '!')
    //         ->line(ucfirst($this->subTask->title) . ' ' . __('email.subTaskComplete.subject') . '.')
    //         ->line((!is_null($this->subTask->task->project)) ? __('app.project') . ' - ' . ucfirst($this->subTask->task->project->project_name) : '')
    //         ->action(__('email.loginDashboard'), url('/'))
    //         ->line(__('email.thankyouNote'));
    // }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->subTask->task->id,
            'created_at' => $this->subTask->created_at->format('Y-m-d H:i:s'),
            'heading' => $this->subTask->title
        ];
    }
}

<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\Task;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Messages\MailMessage;

class NewClientTask extends BaseNotification
{


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $task;
    private $user;
    private $emailSetting;
    public function __construct(Task $task)
    {
        parent::__construct();
        $this->task = $task;
        $this->emailSetting = EmailNotificationSetting::where('setting_name', 'User Assign to Task')->first();
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
        $content = ucfirst($this->task->heading) . '<p>
            <b style="color: green">' . __('app.dueOn') . ' : ' . $this->task->due_date->format('d M, Y') . '</b>
        </p>';

        return (new MailMessage)
            ->subject(__('email.newClientTask.subject') . ' - ' . config('app.name') . '!')
            ->greeting(__('email.hello') . ' ' . ucwords($notifiable->name) . '!')
            ->markdown('mail.task.task-created-client-notification', ['content' => $content]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->task->toArray();
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        //        $slack = SlackSetting::first();
        //        if(count($notifiable->employee) > 0 && (!is_null($notifiable->employee[0]->slack_username) && ($notifiable->employee[0]->slack_username != ''))){
        //            return (new SlackMessage())
        //                ->from(config('app.name'))
        //                ->image($slack->slack_logo_url)
        //                ->to('@' . $notifiable->employee[0]->slack_username)
        //                ->content(__('email.newTask.subject'));
        //        }
        //        return (new SlackMessage())
        //            ->from(config('app.name'))
        //            ->image($slack->slack_logo_url)
        //            ->content('This is a redirected notification. Add slack username for *'.ucwords($notifiable->name).'*');
    }
}

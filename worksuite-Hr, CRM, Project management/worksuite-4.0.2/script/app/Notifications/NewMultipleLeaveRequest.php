<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\Leave;
use App\SlackSetting;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class NewMultipleLeaveRequest extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $leave;
    private $multiDates;
    public function __construct(Leave $leave, $multiDates)
    {
        $this->leave = $leave;
        $this->multiDates = $multiDates;
        $this->emailSetting = EmailNotificationSetting::where('slug', 'new-leave-application')->first();
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

        if ($this->emailSetting->send_slack == 'yes') {
            array_push($via, 'slack');
        }

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
        $user = $notifiable;
        $dates = explode(',', $this->multiDates);
        $emailDate = "";

        foreach ($dates as $key => $date) {
            $emailDate .= ($key + 1) . ". " . $date . "<br>";
        }

        $content = __('email.leaves.subject') . ' ' . __('app.from') . ' ' . ucwords($this->leave->user->name) . '.' . '<p><b>' . __('modules.leaves.leaveType') . ':</b> ' . $this->leave->type->type_name . '</p><p><b>' . __('modules.leaves.reason') . '</b></p><p>' . $this->leave->reason . '</p><p><b>' . __('app.leaveDate') . '</b></p><p>' . $emailDate . '</p>';

        return (new MailMessage)
            ->subject(__('email.leaves.subject') . ' - ' . config('app.name'))
            ->greeting(__('email.hello') . ' ' . ucwords($user->name) . '!')
            ->markdown('mail.leaves.multiple', ['content' => $content]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return $this->leave->toArray();
    }

    public function toSlack($notifiable)
    {
        $slack = SlackSetting::setting();
        if (count($notifiable->employee) > 0 && (!is_null($notifiable->employee[0]->slack_username) && ($notifiable->employee[0]->slack_username != ''))) {
            return (new SlackMessage())
                ->from(config('app.name'))
                ->image($slack->slack_logo_url)
                ->to('@' . $notifiable->employee[0]->slack_username)
                ->content(__('email.leaves.subject') . "\n" . ucwords($this->leave->user->name) . "\n" . '*' . __('app.date') . '*: ' . $this->leave->leave_date->format('d M, Y') . "\n" . '*' . __('modules.leaves.leaveType') . '*: ' . $this->leave->type->type_name . "\n" . '*' . __('modules.leaves.reason') . '*' . "\n" . $this->leave->reason);
        }
        return (new SlackMessage())
            ->from(config('app.name'))
            ->image($slack->slack_logo_url)
            ->content('This is a redirected notification. Add slack username for *' . ucwords($notifiable->name) . '*');
    }

    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->subject(__('email.leaves.subject'))
            ->body('by ' . ucwords($this->leave->user->name));
    }
}

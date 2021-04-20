<?php

namespace App\Notifications;

use App\EmailNotificationSetting;
use App\Leave;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MultipleLeaveApplication extends Notification implements ShouldQueue
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
        $dates = explode(',', $this->multiDates);
        $emailDate = __('app.leaveDate'). "<br>";

        foreach ($dates as $key => $date) {
            $emailDate .= ($key + 1) . ". " . $date . "<br>";
        }

        return (new MailMessage)
            ->subject(__('email.leave.applied') . ' - ' . config('app.name'))
            ->greeting(__('email.hello') . ' ' . ucwords($notifiable->name) . '!')
            ->markdown('mail.leaves.multiple', ['content' => $emailDate]);
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
}

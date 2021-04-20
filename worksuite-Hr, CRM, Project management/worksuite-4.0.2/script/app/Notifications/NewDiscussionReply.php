<?php

namespace App\Notifications;

use App\DiscussionReply;
use App\EmailNotificationSetting;
use App\SlackSetting;
use App\Task;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;
use NotificationChannels\OneSignal\OneSignalChannel;
use NotificationChannels\OneSignal\OneSignalMessage;

class NewDiscussionReply extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $discussionReply;
    private $created_at;
    public function __construct(DiscussionReply $discussionReply)
    {
        $this->discussionReply = $discussionReply;
        $this->emailSetting = EmailNotificationSetting::where('slug', 'discussion-reply')->first();
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
        return (new MailMessage)
            ->subject(ucwords($this->discussionReply->user->name) . ' '. __('email.discussionReply.subject') . $this->discussionReply->discussion->title . ' - ' . config('app.name') . '!')
            ->greeting(__('email.hello') . ' ' . ucwords($notifiable->name) . '!')
            ->line(__('email.discussionReply.text') . ' ' . $this->discussionReply->discussion->title . ':-')
            ->line(new HtmlString($this->discussionReply->body))
            ->action(__('email.loginDashboard'), url('/'))
            ->line(__('email.thankyouNote'));
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
            'id' => $this->discussionReply->id,
            'title' => $this->discussionReply->discussion->title,
            'discussion_id' => $this->discussionReply->discussion_id,
            'user' => $this->discussionReply->user->name,
            'body' => $this->discussionReply->body,
            'project_id' => $this->discussionReply->discussion->project_id
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
        $slack = SlackSetting::setting();
        if (count($notifiable->employee) > 0 && (!is_null($notifiable->employee[0]->slack_username) && ($notifiable->employee[0]->slack_username != ''))) {
            return (new SlackMessage())
                ->from(config('app.name'))
                ->image($slack->slack_logo_url)
                ->to('@' . $notifiable->employee[0]->slack_username)
                ->content('*' . ucwords($this->discussionReply->user->name) . ' '. __('email.discussionReply.subject') . $this->discussionReply->discussion->title . '*' . "\n" . $this->discussionReply->body);
        }
        return (new SlackMessage())
            ->from(config('app.name'))
            ->image($slack->slack_logo_url)
            ->content('This is a redirected notification. Add slack username for *' . ucwords($notifiable->name) . '*');
    }

    public function toOneSignal($notifiable)
    {
        return OneSignalMessage::create()
            ->subject(__('email.discussionReply.subject'))
            ->body(ucfirst($this->discussionReply->discussion->title));
    }
}

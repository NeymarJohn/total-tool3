<?php

namespace App\Notifications;

use Illuminate\Notifications\Channels\DatabaseChannel as BaseDatabaseChannel;

use Illuminate\Notifications\Notification;

class MyDatabaseChannel extends BaseDatabaseChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function send($notifiable, Notification $notification)
    {
        $data = [
            'id' => $notification->id,
            'type' => get_class($notification),
            'data' => $this->getData($notifiable, $notification),
            'read_at' => null,
        ];
        if(company()){
            $data['company_id'] = company()->id;
        }
        return $notifiable->routeNotificationFor('database')->create($data);
    }
}
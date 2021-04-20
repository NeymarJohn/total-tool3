<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailNotificationSetting extends BaseModel
{
    protected $guarded = ['id'];

    public static function userAssignTask()
    {
        return cache()->remember(
            'user-assign-task-notification',
            60 * 60 * 24,
            function () {
                return EmailNotificationSetting::where('slug', 'user-assign-to-task')->first();
            }
        );
    }
}

<?php

namespace App;

use App\Observers\TaskCommentObserver;
use Illuminate\Database\Eloquent\Model;

class TaskComment extends BaseModel
{
    protected static function boot()
    {
        parent::boot();
        static::observe(TaskCommentObserver::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }
}

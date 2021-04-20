<?php

namespace App;

use App\Observers\TaskCommentObserver;
use App\Observers\TaskNoteObserver;
use Illuminate\Database\Eloquent\Model;

class TaskNote extends BaseModel
{

    protected static function boot()
    {
        parent::boot();
        static::observe(TaskNoteObserver::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }
}

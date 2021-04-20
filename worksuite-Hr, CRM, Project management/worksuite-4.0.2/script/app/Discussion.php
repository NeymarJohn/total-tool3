<?php

namespace App;

use App\Observers\DiscussionObserver;
use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    protected static function boot()
    {
        parent::boot();
        static::observe(DiscussionObserver::class);
    }

    protected $guarded = ['id'];
    protected $dates = ['last_reply_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function last_reply_by()
    {
        return $this->belongsTo(User::class, 'last_reply_by_id');
    }

    public function replies()
    {
        return $this->hasMany(DiscussionReply::class, 'discussion_id');
    }

    public function category()
    {
        return $this->belongsTo(DiscussionCategory::class, 'discussion_category_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}

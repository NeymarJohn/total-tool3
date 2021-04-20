<?php

namespace App;

use App\Observers\SupportTicketObserver;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportTicket extends BaseModel
{
    use SoftDeletes;

    protected $dates = ['deleted_at','created_at','updated_at'];
    protected $appends = ['created_on','updated_on'];

    protected static function boot()
    {
        parent::boot();

        static::observe(SupportTicketObserver::class);

    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id')->withoutGlobalScopes(['active']);
    }

    public function reply()
    {
        return $this->hasMany(SupportTicketReply::class, 'support_ticket_id');
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }

    public function getCreatedOnAttribute()
    {
        if (!is_null($this->created_at)) {
            return $this->created_at->format('d M Y H:i');
        }
        return "";
    }
    public function getUpdatedOnAttribute()
    {
        if (!is_null($this->updated_at)) {
            return $this->updated_at->format('Y-m-d H:i a');
        }
        return "";
    }
}

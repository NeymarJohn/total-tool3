<?php

namespace App;

use App\Observers\TicketReplyObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketReply extends BaseModel
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();

        static::observe(TicketReplyObserver::class);
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }

    public function files(){
        return $this->hasMany(TicketFile::class, 'ticket_reply_id');
    }

    public function ticket(){
        return $this->belongsTo(Ticket::class);
    }
}

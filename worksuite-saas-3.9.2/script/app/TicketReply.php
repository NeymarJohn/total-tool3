<?php

namespace App;

use App\Observers\TicketReplyObserver;
use App\Scopes\CompanyScope;
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

        static::addGlobalScope(new CompanyScope);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes([CompanyScope::class, 'active']);
    }
    public function files()
    {
        return $this->hasMany(TicketFile::class, 'ticket_reply_id');
    }
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id')->withTrashed();
    }
}

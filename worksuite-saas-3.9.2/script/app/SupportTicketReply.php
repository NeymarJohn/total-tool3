<?php

namespace App;

use App\Observers\SupportTicketReplyObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupportTicketReply extends BaseModel
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();

        static::observe(SupportTicketReplyObserver::class);

    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active', CompanyScope::class,]);
    }
    public function files()
    {
        return $this->hasMany(SupportTicketFile::class, 'support_ticket_reply_id');
    }
    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id')->withTrashed();
    }
}

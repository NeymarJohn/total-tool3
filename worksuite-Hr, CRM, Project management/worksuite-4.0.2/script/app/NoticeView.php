<?php

namespace App;

use App\Observers\NoticeObserver;
use App\Observers\NoticeViewObserver;
use App\Scopes\CompanyScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class NoticeView extends BaseModel
{
    protected $dates = ['created_at', 'updated_at'];

    protected static function boot()
    {
        parent::boot();

    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(['active']);
    }

}

<?php

namespace App;

use App\Observers\NoticeObserver;
use App\Scopes\CompanyScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Notice extends BaseModel
{
    use Notifiable;
    protected $appends = ['notice_date', 'file_url'];

    protected static function boot()
    {
        parent::boot();

        static::observe(NoticeObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function member()
    {
        return $this->hasMany(NoticeView::class, 'notice_id');
    }

    public function getNoticeDateAttribute()
    {
        if (!is_null($this->created_at)) {
            return Carbon::parse($this->created_at)->format('d F, Y');
        }
        return "";
    }

    public function getFileUrlAttribute()
    {
        return asset_url_local_s3('notice-attachment/'.$this->attachment);
    }
}

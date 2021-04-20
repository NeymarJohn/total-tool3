<?php

namespace App;

use App\Observers\NoticeObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Notice extends BaseModel
{
    use Notifiable;
    protected $appends = ['notice_date'];

    protected static function boot()
    {
        parent::boot();
        static::observe(NoticeObserver::class);
    }

    public function member()
    {
        return $this->hasMany(NoticeView::class, 'notice_id');
    }

    public function getNoticeDateAttribute(){
        if(!is_null($this->created_at)){
            return Carbon::parse($this->created_at)->format('d F, Y');
        }
        return "";
    }
    public function department()
    {
        return $this->belongsTo(Team::class, 'department_id', 'id');
    }
}

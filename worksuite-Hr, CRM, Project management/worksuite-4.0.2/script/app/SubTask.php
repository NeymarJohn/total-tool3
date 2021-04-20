<?php

namespace App;
use App\Observers\SubTaskObserver;
use Illuminate\Database\Eloquent\Model;

class SubTask extends BaseModel
{
    protected $dates = ['due_date'];

    protected static function boot()
    {
        parent::boot();

        static::observe(SubTaskObserver::class);
    }
    
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}

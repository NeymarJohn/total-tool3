<?php namespace App;

use App\Observers\PinnedObserver;
use App\Scopes\CompanyScope;

class Pinned extends BaseModel
{
    protected $table = 'pinned';
    protected $guarded = ['id'];

    public function user(){
      return $this->belongsTo(User::class, 'user_id');
    }

    public function project(){
      return $this->belongsTo(Project::class, 'project_id');
    }

    public function task(){
      return $this->belongsTo(Task::class, 'task_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::observe(PinnedObserver::class);
    }
}

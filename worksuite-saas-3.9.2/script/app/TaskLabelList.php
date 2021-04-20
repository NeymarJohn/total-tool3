<?php

namespace App;


use App\Observers\TaskLabelObserver;
use App\Scopes\CompanyScope;

class TaskLabelList extends BaseModel
{
    protected $table = 'task_label_list';

    protected $guarded = ['id'];
    public $appends = ['label_color'];

    protected static function boot()
    {
        parent::boot();

        static::observe(TaskLabelObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function getLabelColorAttribute()
    {
        if ($this->color) {
            return $this->color;
        }

        return '#3b0ae1';
    }
}

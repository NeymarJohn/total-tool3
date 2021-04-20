<?php

namespace App;

use App\Observers\SkillsObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Skill extends BaseModel
{
    protected $table = 'skills';
    protected $fillable = ['name'];

    protected static function boot()
    {
        parent::boot();

        static::observe(SkillsObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;
use App\Observers\ProjectUserNotesObserver;

class ProjectUserNotes extends Model
{
    protected $table = 'project_user_notes';
    protected $fillable = ['user_id', 'project_notes_id'];

    protected static function boot()
    {
        parent::boot();
        static::observe(ProjectUserNotesObserver::class);
        static::addGlobalScope(new CompanyScope);
    }
}
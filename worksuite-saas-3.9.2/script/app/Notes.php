<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;
use App\Observers\NotesObserver;

class Notes extends Model
{
    protected $table = 'notes';
   
    protected static function boot()
    {
        parent::boot();
        static::observe(NotesObserver::class);
        static::addGlobalScope(new CompanyScope);
    }
    
    public function member()
    {
        return $this->hasMany(ClientUserNotes::class, 'note_id');
    }
   
}
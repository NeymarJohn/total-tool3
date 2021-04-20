<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\CompanyScope;
use App\Observers\ClientUserNotesObserver;

class ClientUserNotes extends Model
{
    protected $table = 'client_user_notes';
    protected $fillable = ['user_id', 'note_id'];

    protected static function boot()
    {
        parent::boot();
        static::observe(ClientUserNotesObserver::class);
        static::addGlobalScope(new CompanyScope);
    }
}
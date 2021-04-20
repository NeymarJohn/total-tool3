<?php

namespace App;

use App\Observers\FileStorageObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;

class FileStorage extends BaseModel
{
    protected $table = 'file_storage';

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();
        static::observe(FileStorageObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}

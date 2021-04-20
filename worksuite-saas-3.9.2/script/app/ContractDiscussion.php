<?php

namespace App;

use App\Observers\ContractDiscussionObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ContractDiscussion extends BaseModel
{
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    protected static function boot()
    {
        parent::boot();

        static::observe(ContractDiscussionObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'from', 'id');
    }
}

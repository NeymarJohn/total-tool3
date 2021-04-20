<?php namespace App;

use App\Observers\RoleObserver;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Builder;
use Trebol\Entrust\EntrustRole;
use Illuminate\Support\Facades\Auth;

class Role extends EntrustRole
{

    public static function boot()
    {
        parent::boot();

        static::observe(RoleObserver::class);

        static::addGlobalScope(new CompanyScope);
    }

    public function permissions(){
       return $this->hasMany(PermissionRole::class, 'role_id');
    }

    public function roleuser(){
       return $this->hasMany(RoleUser::class, 'role_id');
    }
}

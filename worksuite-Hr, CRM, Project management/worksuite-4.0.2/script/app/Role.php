<?php

namespace App;

use Trebol\Entrust\EntrustRole;

class Role extends EntrustRole
{
   public function permissions()
   {
      return $this->hasMany(PermissionRole::class, 'role_id');
   }

   public function roleuser()
   {
      return $this->hasMany(RoleUser::class, 'role_id');
   }
   
}

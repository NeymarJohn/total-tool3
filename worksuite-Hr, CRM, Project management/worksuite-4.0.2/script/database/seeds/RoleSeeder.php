<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;
use App\RoleUser;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = "Manager";
        $role->display_name = ucwords('Manager');
        $role->save();

        $roleId = $role->id;
        $permissions = Permission::all();

        $role = Role::findOrFail($roleId);
        $role->perms()->sync([]);
        $role->attachPermissions($permissions);

        // $user_id = ['2'];

        // foreach($user_id as $user){
        //     $roleUser = new RoleUser();
        //     $roleUser->user_id = $user;
        //     $roleUser->role_id = $roleId;
        //     $roleUser->save();
        // }
    }
}

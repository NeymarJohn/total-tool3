<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\Role\StoreRole;
use App\Http\Requests\Role\StoreUserRole;
use App\Http\Requests\Role\UpdateRole;
use App\Module;
use App\Permission;
use App\PermissionRole;
use App\Role;
use App\RoleUser;
use App\User;
use Illuminate\Http\Request;

class ManageRolePermissionController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.rolesPermission';
        $this->pageIcon = 'ti-lock';
    }

    public function index()
    {
        $this->roles = Role::with('permissions')->where('id', '>', 3)->get();

        $this->totalPermissions = Permission::count();
        $this->modulesData = Module::with('permissions')->get();
        return view('admin.role-permission.index', $this->data);
    }

    public function store(Request $request)
    {
        $roleId = $request->roleId;
        $permissionId = $request->permissionId;

        if ($request->assignPermission == 'yes') {
            $rolePermission = new PermissionRole();
            $rolePermission->permission_id = $permissionId;
            $rolePermission->role_id = $roleId;
            $rolePermission->save();
        } else {
            PermissionRole::where('role_id', $roleId)->where('permission_id', $permissionId)->delete();
        }

        return Reply::dataOnly(['status' => 'success']);
    }

    public function assignAllPermission(Request $request)
    {
        $roleId = $request->roleId;
        $permissions = Permission::all();

        $role = Role::findOrFail($roleId);
        $role->perms()->sync([]);
        $role->attachPermissions($permissions);
        return Reply::dataOnly(['status' => 'success']);
    }

    public function removeAllPermission(Request $request)
    {
        $roleId = $request->roleId;

        $role = Role::findOrFail($roleId);
        $role->perms()->sync([]);

        return Reply::dataOnly(['status' => 'success']);
    }

    public function showMembers($id)
    {
        $this->role = Role::findOrFail($id);
        $this->employees = User::doesntHave('role', 'and', function ($query) use ($id) {
            $query->where('role_user.role_id', $id);
        })
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->distinct('users.id')
            ->where('roles.name', '<>', 'client')
            ->where('users.id', '<>', user()->id)
            ->get();

        return view('admin.role-permission.members', $this->data);
    }

    public function storeRole(StoreRole $request)
    {
        $roleUser = new Role();
        $roleUser->name = $request->name;
        $roleUser->display_name = ucwords($request->name);
        $roleUser->save();
        return Reply::success(__('messages.roleCreated'));
    }

    public function assignRole(StoreUserRole $request)
    {
        $employeeRole = Role::where('name', 'employee')->first();
        foreach ($request->user_id as $user) {
            RoleUser::where('user_id', $user)->delete();

            $roleUser = new RoleUser();
            $roleUser->user_id = $user;
            $roleUser->role_id = $employeeRole->id;
            $roleUser->save();

            $roleUser = new RoleUser();
            $roleUser->user_id = $user;
            $roleUser->role_id = $request->role_id;
            $roleUser->save();
        }
        return Reply::success(__('messages.roleAssigned'));
    }

    public function detachRole(Request $request)
    {
        $user = User::withoutGlobalScopes(['active'])->findOrFail($request->userId);
        $user->detachRole($request->roleId);
        return Reply::dataOnly(['status' => 'success']);
    }

    public function deleteRole(Request $request)
    {
        Role::whereId($request->roleId)->delete();
        return Reply::dataOnly(['status' => 'success']);
    }

    public function create()
    {
        $this->roles = Role::all();
        return view('admin.role-permission.create', $this->data);
    }

    public function update(UpdateRole $request, $id)
    {
        $roleUser = Role::findOrFail($id);
        $roleUser->name = $request->value;
        $roleUser->display_name = ucwords($request->value);
        $roleUser->save();

        return Reply::successWithData(__('messages.roleUpdated'), ['display_name' => $roleUser->display_name]);
    }
}

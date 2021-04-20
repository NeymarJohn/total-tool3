<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuleSetting extends BaseModel
{

    protected $guarded = ['id'];

    public static function checkModule($moduleName)
    {

        $user = auth()->user();

        $module = ModuleSetting::where('module_name', $moduleName);

        if ($user->hasRole('admin')) {
            $module = $module->where('type', 'admin');

        } elseif ($user->hasRole('client')) {
            $module = $module->where('type', 'client');

        } elseif ($user->hasRole('employee')) {
            $module = $module->where('type', 'employee');
        }

        $module = $module->where('status', 'active');

        $module = $module->first();

        return $module ? true : false;
    }
}

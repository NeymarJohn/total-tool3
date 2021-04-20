<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\ModuleSetting;
use Illuminate\Http\Request;

class ModuleSettingsController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.moduleSettings';
        $this->pageIcon = 'icon-settings';
    }

    public function index(Request $request)
    {

        if ($request->has('type')) {
            if ($request->get('type') == 'employee') {
                $this->modulesData = ModuleSetting::where('type', 'employee')->get();
                $this->type = 'employee';
            } elseif ($request->get('type') == 'client') {
                $this->modulesData = ModuleSetting::where('type', 'client')->get();
                $this->type = 'client';
            }
        } else {
            $this->modulesData = ModuleSetting::where('type', 'admin')->get();
            $this->type = 'admin';
        }

        return view('admin.module-settings.index', $this->data);
    }

    public function update(Request $request)
    {

        $setting = ModuleSetting::findOrFail($request->id);
        $setting->status = $request->status;
        $setting->save();

        session()->forget('user_modules');

        return Reply::success(__('messages.settingsUpdated'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\LeaveType;
use App\Setting;
use Illuminate\Http\Request;

class LeavesSettingController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.leaveSettings';
        $this->pageIcon = 'ti-settings';
        $this->middleware(function ($request, $next) {
            if (!in_array('leaves', $this->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        $this->leaveTypes = LeaveType::all();
        return view('admin.leaves-settings.index', $this->data);
    }

    public function store(Request $request)
    {
        $setting = cache()->remember(
            'global-setting',
            60 * 60 * 24,
            function () {
                return \App\Setting::first();
            }
        );
        $setting->leaves_start_from = $request->leaveCountFrom;
        $setting->save();
        $setting = cache()->forget('global-setting');

        return Reply::success(__('messages.settingsUpdated'));
    }
}

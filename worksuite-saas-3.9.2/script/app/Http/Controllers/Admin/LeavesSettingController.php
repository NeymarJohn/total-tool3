<?php

namespace App\Http\Controllers\Admin;

use App\Company;
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
            if(!in_array('leaves',$this->user->modules)){
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(){
        $this->leaveTypes = LeaveType::all();
        return view('admin.leaves-settings.index', $this->data);
    }

    public function store(Request $request){
        $setting = Company::where('id', company()->id)->first();
        $setting->leaves_start_from = $request->leaveCountFrom;
        $setting->save();

        return Reply::success(__('messages.settingsUpdated'));
    }
}

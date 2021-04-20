<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\MessageSetting;
use Illuminate\Http\Request;

class MessageSettingsController extends AdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.messageSettings';
        $this->pageIcon = 'ti-settings';
        $this->middleware(function ($request, $next) {
            if(!in_array('messages',$this->user->modules)){
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(){
        $this->messageSettings = MessageSetting::first();
        return view('admin.message-settings.index', $this->data);
    }

    public function update(Request $request, $id){
        $msgSetting = MessageSetting::first();
        if($request->allow_client_admin){
            $msgSetting->allow_client_admin = 'yes';
        }
        else{
            $msgSetting->allow_client_admin = 'no';
        }
        if($request->allow_client_employee){
            $msgSetting->allow_client_employee = 'yes';
        }
        else{
            $msgSetting->allow_client_employee = 'no';
        }
        $msgSetting->save();

        return Reply::success(__('messages.settingsUpdated'));
    }
}

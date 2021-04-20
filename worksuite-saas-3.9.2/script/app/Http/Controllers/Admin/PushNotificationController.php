<?php

namespace App\Http\Controllers\Admin;

use App\EmailNotificationSetting;
use App\Helper\Reply;
use App\Notifications\TestPush;
use App\PushNotificationSetting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PushNotificationController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.pushNotifications';
        $this->pageIcon = 'fa fa-bell';
    }

    public function index(){
        $this->emailSettings = EmailNotificationSetting::all();
        $this->pushSettings = PushNotificationSetting::first();
        return view('admin.push-settings.index', $this->data);
    }

    public function update(Request $request, $id){
        $setting = PushNotificationSetting::findOrFail($id);
        $setting->onesignal_app_id = $request->onesignal_app_id;
        $setting->onesignal_rest_api_key = $request->onesignal_rest_api_key;
        $setting->status = $request->status;

        if(isset($request->removeImage) && $request->removeImage == 'on'){
            if($setting->slack_logo){ // Remove stored Image
                File::delete('user-uploads/notification-logo/'.$setting->notification_logo);
            }

            $setting->slack_logo = null; // Remove image from database
        }
        elseif ($request->hasFile('notification_logo')) {
            $setting->notification_logo = $request->notification_logo->hashName();
            $request->notification_logo->store('notification-logo');
        }

        $setting->save();
        
        session()->forget('push_setting');

        return Reply::redirect(route('admin.push-notification-settings.index'), __('messages.settingsUpdated'));
    }

    public function updatePushNotification(Request $request){
        $setting = EmailNotificationSetting::findOrFail($request->id);
        $setting->send_push = $request->send_push;
        $setting->save();

        return Reply::success(__('messages.settingsUpdated'));
    }

    public function sendTestNotification(){
        $user = User::find($this->user->id);
        // Notify User
        $user->notify(new TestPush());

        return Reply::success('Test notification sent.');
    }
}

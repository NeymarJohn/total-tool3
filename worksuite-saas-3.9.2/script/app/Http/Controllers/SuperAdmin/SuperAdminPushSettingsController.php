<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Helper\Reply;
use App\PushNotificationSetting;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Notifications\TestPush;

class SuperAdminPushSettingsController extends SuperAdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.pushNotificationSetting';
        $this->pageIcon = 'icon-settings';
    }

    public function index() {
        $this->pushSettings = PushNotificationSetting::first();
        return view('super-admin.push-settings.index', $this->data);
    }

    public function update(Request $request,  $id) {
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


        return Reply::success(__('messages.settingsUpdated'));
    }

    public function sendTestNotification(){
        $user = User::find($this->user->id);
        // Notify User
        $user->notify(new TestPush());

        return Reply::success('Test notification sent.');
    }

}

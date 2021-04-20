<?php

namespace App\Http\Controllers\Admin;

use App\EmailNotificationSetting;
use App\Helper\Files;
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
        $this->emailSettings = email_notification_setting();
        $this->pushSettings = push_setting();
        return view('admin.push-settings.index', $this->data);
    }

    public function update(Request $request, $id){
        $setting = PushNotificationSetting::findOrFail($id);
        $setting->onesignal_app_id = $request->onesignal_app_id;
        $setting->onesignal_rest_api_key = $request->onesignal_rest_api_key;
        $setting->status = $request->status;
        $setting->save();

        session()->forget('push_setting');

        return Reply::redirect(route('admin.push-notification-settings.index'), __('messages.settingsUpdated'));
    }

    public function updatePushNotification(Request $request){
        $setting = EmailNotificationSetting::findOrFail($request->id);
        $setting->send_push = $request->send_push;
        $setting->save();

        session()->forget('email_notification_setting');

        return Reply::success(__('messages.settingsUpdated'));
    }

    public function sendTestNotification(){
        $user = User::find($this->user->id);
        // Notify User
        $user->notify(new TestPush());

        return Reply::success('Test notification sent.');
    }
}

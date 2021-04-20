<?php

namespace App\Http\Controllers\Admin;

use App\EmailNotificationSetting;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\Admin\SlackSettings\UpdateRequest;
use App\Notifications\TestSlack;
use App\SlackSetting;
use App\User;
use Illuminate\Http\Request;

class SlackSettingController extends AdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.slackSettings';
        $this->pageIcon = 'fa fa-slack';
    }

    public function index(){
        $this->emailSettings = EmailNotificationSetting::all();
        $this->slackSettings = SlackSetting::first();
        return view('admin.slack-settings.index', $this->data);
    }

    public function update(UpdateRequest $request, $id){

        $setting = SlackSetting::first();
        $setting->slack_webhook = $request->slack_webhook;

        if(isset($request->removeImage) && $request->removeImage == 'on'){
            Files::deleteFile($request->slack_logo,'slack-logo');
            $setting->slack_logo = null; // Remove image from database
        }

        elseif ($request->hasFile('slack_logo')) {
            $setting->slack_logo = Files::upload($request->slack_logo, 'slack-logo');
        }

        $setting->save();

        return Reply::redirect(route('admin.slack-settings.index'), __('messages.settingsUpdated'));
    }

    public function updateSlackNotification(Request $request){
        $setting = EmailNotificationSetting::findOrFail($request->id);
        $setting->send_slack = $request->send_slack;
        $setting->save();

        return Reply::success(__('messages.settingsUpdated'));
    }

    public function sendTestNotification(){
        $user = User::find($this->user->id);
        // Notify User
        $user->notify(new TestSlack());

        return Reply::success('Test notification sent.');
    }
}

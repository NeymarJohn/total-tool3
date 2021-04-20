<?php

namespace App\Http\Controllers\Admin;

use App\EmailNotificationSetting;
use App\Helper\Reply;
use App\Http\Requests\SmtpSetting\UpdateSmtpSetting;
use App\Notifications\TestEmail;
use App\SmtpSetting;
use App\User;
use Illuminate\Http\Request;

class EmailNotificationSettingController extends AdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.emailSettings';
        $this->pageIcon = 'icon-settings';
    }

    public function index() {
        $company = company();
        $this->emailSettings = EmailNotificationSetting::where('company_id', $company->id)->get();
        $this->smtpSetting = SmtpSetting::first();
        return view('admin.email-settings.index', $this->data);
    }

    public function update(Request $request){
        $setting = EmailNotificationSetting::findOrFail($request->id);
        $setting->send_email = $request->send_email;
        $setting->save();

        return Reply::success(__('messages.settingsUpdated'));
    }

    public function updateMailConfig(UpdateSmtpSetting $request) {
        $smtp = SmtpSetting::first();
        $smtp->mail_driver = $request->mail_driver;
        $smtp->mail_host = $request->mail_host;
        $smtp->mail_port = $request->mail_port;
        $smtp->mail_username = $request->mail_username;
        $smtp->mail_password = $request->mail_password;
        $smtp->mail_from_name = $request->mail_from_name;
        $smtp->mail_from_email = $request->mail_from_email;
        $smtp->mail_encryption = $request->mail_encryption;
        $smtp->save();

        return Reply::success(__('messages.settingsUpdated'));
    }

    public function sendTestEmail(){
        $user = User::find($this->user->id);
        // Notify User
        $user->notify(new TestEmail());

        return Reply::success('Test email sent.');
    }

}

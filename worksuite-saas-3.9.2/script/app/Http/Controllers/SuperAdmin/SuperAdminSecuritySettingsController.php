<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Company;
use App\GlobalSetting;
use App\Helper\Reply;
use App\Http\Requests\SuperAdmin\Settings\UpdateSecuritySettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;



class SuperAdminSecuritySettingsController extends SuperAdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.security';
        $this->pageIcon = 'icon-settings';
    }

    public function index() {
        $this->globalSetting = GlobalSetting::first();
        return view('super-admin.security-settings.index', $this->data);
    }

    public function create(Request $request)
    {
            $this->global = GlobalSetting::first();
            $this->captcha_key = $request->google_captcha_site_key;    
            $this->google_captcha_secret = $request->google_captcha_secret; 
            $this->google_captcha_version = $request->version; 
            $this->google_recaptcha_status = $request->google_recaptcha_status;

            $this->system_update = $request->system_update;
            $this->app_debug = $request->app_debug;
            $this->enable_register = $request->enable_register;
            $this->email_verification = $request->email_verification;

            return view('super-admin.security-settings.create', $this->data);
    }
    public function showModal(UpdateSecuritySettings $request)
    {
        return Reply::dataOnly(['status' => 'success']);
    }
    
    public function update(Request $request, $id) {
        $setting = GlobalSetting::findOrFail($id);
        $setting->system_update = $request->system_update == 'true' ? 1 : 0;
        $setting->email_verification = $request->app_debug && $request->app_debug == 'true' ? 1 : 0;
        $setting->enable_register = $request->enable_register && $request->enable_register == 'true' ? 1 : 0;
        $setting->app_debug = $request->app_debug && $request->app_debug == 'true' ? 1 : 0;

        $setting->google_recaptcha_key = $request->google_recaptcha_key;
        $setting->google_recaptcha_secret = $request->google_recaptcha_secret;
        $setting->google_recaptcha_status = $request->google_recaptcha_status ? 1 : 0;
        $setting->google_captcha_version = $request->google_captcha_version;
        if(!$request->google_recaptcha_status){
            $setting->system_update = $request->has('system_update') && $request->input('system_update') == 'on' ? 1 : 0;
            $setting->email_verification = $request->has('email_verification') && $request->input('email_verification') == 'on' ? 1 : 0;
            $setting->app_debug = $request->has('app_debug') && $request->input('app_debug') == 'on' ? 1 : 0;
            $setting->enable_register = $request->has('enable_register') && $request->input('enable_register') == 'on' ? 1 : 0;
            $setting->google_captcha_version = 'v3';
        }
        $setting->save();

        if($setting->google_recaptcha_status == 0){

            Company::where('lead_form_google_captcha', 1)
                    ->orwhere(function($q) use ($id) {
                        $q->Where('ticket_form_google_captcha', 1);
                    })->update([
                        'ticket_form_google_captcha' => 0,
                        'lead_form_google_captcha' => 0,
                    ]);
        }
        session()->forget('company');
        return Reply::successWithData(__('messages.updateSuccess'), []);
    }
    
    

}

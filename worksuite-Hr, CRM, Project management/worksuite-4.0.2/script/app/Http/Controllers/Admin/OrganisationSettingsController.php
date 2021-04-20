<?php

namespace App\Http\Controllers\Admin;

use App\Currency;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\Settings\UpdateOrganisationSettings;
use App\Setting;
use App\Traits\CurrencyExchange;
use Carbon\Carbon;

use Illuminate\Http\Request;

class OrganisationSettingsController extends AdminBaseController
{

    use CurrencyExchange;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.accountSettings';
        $this->pageIcon = 'icon-settings';
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->timezones = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);
        $this->currencies = Currency::all();
        $this->dateObject = Carbon::now();
        $this->cachedFile = \File::exists(base_path('bootstrap/cache/config.php'));
        return view('admin.settings.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateOrganisationSettings $request, $id)
    {
        config(['filesystems.default' => 'local']);

        $setting = cache()->remember(
            'global-setting', 60*60*24, function () {
                return \App\Setting::first();
            }
        );
        $googleCaptcha = $request->has('google_recaptcha') && $request->input('google_recaptcha') == 'on' ? 1 : 0;

        $setting->company_name = $request->input('company_name');
        $setting->company_email = $request->input('company_email');
        $setting->company_phone = $request->input('company_phone');
        $setting->website = $request->input('website');
        $setting->address = $request->input('address');
        $setting->currency_id = $request->input('currency_id');
        $setting->timezone = $request->input('timezone');
        $setting->locale = $request->input('locale');
        $setting->date_format = $request->input('date_format');
        $setting->time_format = $request->input('time_format');
        $setting->app_debug = $request->has('app_debug') && $request->input('app_debug') == 'on' ? 1 : 0;
        $setting->system_update = $request->has('system_update') && $request->input('system_update') == 'on' ? 1 : 0;
        $setting->google_recaptcha = $googleCaptcha;
        $setting->google_recaptcha_key = $request->input('google_recaptcha_key');
        $setting->google_recaptcha_secret = $request->input('google_recaptcha_secret');
        $setting->weather_key = $request->input('weather_key');
        $setting->longitude = $request->input('longitude');
        $setting->latitude = $request->input('latitude');
        $setting->dashboard_clock = $request->has('dashboard_clock') && $request->input('dashboard_clock') == 'on' ? 1 : 0;


        if ($request->hasFile('logo')) {
            Files::deleteFile($setting->logo, 'app-logo');
            $setting->logo = Files::upload($request->logo, 'app-logo');
        }

        if ($request->hasFile('login_background')) {
            Files::deleteFile($setting->login_background, 'login-background');
            $setting->login_background = Files::upload($request->login_background, 'login-background');
        }

        if($googleCaptcha == 0){
            $setting->ticket_form_google_captcha = 0;
            $setting->lead_form_google_captcha = 0;
        }

        $setting->save();

        session()->forget('global_setting');
        cache()->forget('global-setting');
        $setting = cache()->remember(
            'global-setting', 60*60*24, function () {
                return \App\Setting::first();
            }
        );

        $this->updateExchangeRates();

        return Reply::redirect(route('admin.settings.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function changeLanguage(Request $request)
    {
        $setting = cache()->remember(
            'global-setting', 60*60*24, function () {
                return \App\Setting::first();
            }
        );
        $setting->locale = $request->input('lang');
        $setting->save();         
        session()->forget('global_setting');
        cache()->forget('global-setting');
        
        return Reply::success('Language changed successfully.');
    }
}

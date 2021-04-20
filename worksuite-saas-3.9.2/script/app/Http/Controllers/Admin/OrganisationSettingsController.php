<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Currency;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\Settings\UpdateOrganisationSettings;
use App\Traits\CurrencyExchange;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrganisationSettingsController extends AdminBaseController
{

    use CurrencyExchange;

    public function __construct() {
        parent:: __construct();
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
        $this->company    = Company::findOrFail(company()->id);
        $this->currencies = Currency::all();
        $this->dateObject = Carbon::now($this->company->timezone);

        return view('admin.settings.edit', $this->data);
    }

    /**
     * @param UpdateOrganisationSettings $request
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function update(UpdateOrganisationSettings $request, $id)
    {
        $setting = Company::findOrFail($id);
        $setting->update($request->all());

        if ($request->hasFile('logo')) {
            $setting->logo = Files::upload($request->logo, 'app-logo');
        }
        // SUDOMAIN MODULE IS ENABLED
        if ($request->hasFile('login_background') && module_enabled('Subdomain')) {
            $setting->login_background = Files::upload($request->login_background, 'login-background');
        }
        $setting->dashboard_clock = ($request->has('dashboard_clock') && $request->dashboard_clock == 'on') ? 1 : 0;
        $setting->save();

        session()->forget('company_setting');
        session()->forget('company');

        try {
            $this->updateExchangeRates();
        } catch (\Throwable $th) {
        }

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

    public function changeLanguage(Request $request) {
        $setting = Company::where('id', company()->id)->first();
        $setting->locale = $request->input('lang');

        $setting->last_updated_by = $this->user->id;
        $setting->save();
        session()->forget('company_setting');

        return Reply::success('Language changed successfully.');
    }
}

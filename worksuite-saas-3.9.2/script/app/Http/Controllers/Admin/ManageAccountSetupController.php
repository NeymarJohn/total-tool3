<?php

namespace App\Http\Controllers\Admin;

use App\Company;
use App\Currency;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\Settings\UpdateOrganisationSettings;
use App\Http\Requests\UpdateInvoiceSetting;
use App\InvoiceSetting;
use App\Setting;
use App\Traits\CurrencyExchange;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;

class ManageAccountSetupController extends AdminBaseController
{
    use CurrencyExchange;

    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.accountSetup';
        $this->pageIcon = 'icon-gear';
    }

    public function index() {
        $invoiceSetting = InvoiceSetting::first();
        if ($this->company->company_name && $this->company->company_email &&  $this->company->address  && $invoiceSetting->invoice_prefix && $invoiceSetting->estimate_prefix && $invoiceSetting->credit_note_prefix && $invoiceSetting->template && $invoiceSetting->due_after && $invoiceSetting->invoice_terms){
            return Redirect::route('admin.dashboard');
        }
        $this->timezones = \DateTimeZone::listIdentifiers(\DateTimeZone::ALL);
        $this->currencies = Currency::all();
        $this->dateObject = Carbon::now();
        $this->invoiceSetting = InvoiceSetting::first();
        return view('admin.account-setup.edit', $this->data);
    }

    public function update(UpdateOrganisationSettings $request, $id) {
        config(['filesystems.default' => 'local']);

        $setting = Company::findOrFail($id);
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

        if ($request->hasFile('logo')) {
            $setting->logo = Files::upload($request->logo, 'app-logo');
        }
        $setting->last_updated_by = $this->user->id;

        $setting->save();

        session()->forget('company_setting');
        session()->forget('company');

        try {
            $this->updateExchangeRates();
        } catch (\Throwable $th) {
            //
        }

        return Reply::success(__('messages.settingsUpdated'));
    }

    public function updateInvoice(UpdateInvoiceSetting $request, $id) {
        $setting = InvoiceSetting::first();
        $setting->invoice_prefix = $request->invoice_prefix;
        $setting->invoice_digit = $request->invoice_digit;
        $setting->estimate_prefix = $request->estimate_prefix;
        $setting->estimate_digit = $request->estimate_digit;
        $setting->credit_note_prefix = $request->credit_note_prefix;
        $setting->credit_note_digit = $request->credit_note_digit;
        $setting->template       = $request->template;
        $setting->due_after      = $request->due_after;
        $setting->invoice_terms  = $request->invoice_terms;
        $setting->gst_number     = $request->gst_number;
        $setting->show_gst       = $request->has('show_gst') ? 'yes' : 'no';
        $setting->save();

        session()->forget('invoice_setting');
        
        return Reply::redirect(route('admin.dashboard'), __('messages.settingsUpdated'));
    }

}

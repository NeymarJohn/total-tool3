<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\UpdateInvoiceSetting;
use App\InvoiceSetting;

class InvoiceSettingController extends AdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.financeSettings';
        $this->pageIcon = 'icon-gear';
    }

    public function index() {
        $this->invoiceSetting = InvoiceSetting::first();
        return view('admin.invoice-settings.edit', $this->data);
    }

    public function update(UpdateInvoiceSetting $request) {
        $setting = InvoiceSetting::first();
        $setting->invoice_prefix        = $request->invoice_prefix;
        $setting->invoice_digit         = $request->invoice_digit;
        $setting->estimate_prefix       = $request->estimate_prefix;
        $setting->estimate_digit        = $request->estimate_digit;
        $setting->credit_note_prefix    = $request->credit_note_prefix;
        $setting->credit_note_digit     = $request->credit_note_digit;
        $setting->template              = $request->template;
        $setting->due_after             = $request->due_after;
        $setting->invoice_terms         = $request->invoice_terms;
        $setting->estimate_terms        = $request->estimate_terms;
        $setting->gst_number            = $request->gst_number;
        $setting->show_gst              = $request->has('show_gst') ? 'yes' : 'no';
        $setting->hsn_sac_code_show     = $request->has('hsn_sac_code_show') ? 1 : 0;
        $setting->locale                = $request->locale;
        if ($request->hasFile('logo')) {
            Files::deleteFile($setting->logo,'app-logo');
            $setting->logo = Files::upload($request->logo, 'app-logo');
        }
        $setting->save();
        session()->forget('invoice_setting');

        return Reply::success(__('messages.settingsUpdated'));
    }
}

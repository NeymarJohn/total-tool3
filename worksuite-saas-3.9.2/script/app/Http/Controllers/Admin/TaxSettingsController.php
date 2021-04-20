<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\Tax\StoreTax;
use App\Tax;

class TaxSettingsController extends AdminBaseController
{
    public function __construct() {
        parent::__construct();
    }

    public function create()
    {
        $this->taxes = Tax::all();
        return view('admin.taxes.create', $this->data);
    }

    public function store(StoreTax $request)
    {
        $tax = new Tax();
        $tax->tax_name = $request->tax_name;
        $tax->rate_percent = $request->rate_percent;
        $tax->save();

        return Reply::success(__('messages.taxAdded'));
    }
    public function destroy($id)
    {
        Tax::destroy($id);
        return Reply::success(__('messages.taxDelete'));
    }
}

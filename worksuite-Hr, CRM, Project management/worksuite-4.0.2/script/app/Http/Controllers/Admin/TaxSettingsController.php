<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\Tax\StoreTax;
use App\Http\Requests\Tax\UpdateTax;
use App\Tax;
use Illuminate\Http\Request;

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

    public function edit($id)
    {
        $this->tax = Tax::find($id);
        $view = view('admin.taxes.edit', $this->data)->render();
        return Reply::dataOnly(['view' => $view]);
    }

    public function update(UpdateTax $request, $id)
    {
        $tax = Tax::find($id);

        $tax->tax_name = $request->tax_name;
        $tax->rate_percent =$request->rate_percent;
        $tax->save();

        $this->taxes = Tax::all();
        $view = view('admin.taxes.create', $this->data)->render();
        return Reply::successWithData('Tax Successfully Updated.', ['view' => $view]);
    }

    public function destroy(Request $request, $id)
    {
        Tax::destroy($id);
        return Reply::success(__('messages.taxDeleted'));
    }
}

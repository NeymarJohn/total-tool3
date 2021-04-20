<?php

namespace App\Http\Controllers\Admin;

use App\CreditNotes;
use App\Currency;
use App\DataTables\Admin\InvoiceRecurringDataTable;
use App\DataTables\Admin\RecurringInvoicesDataTable;
use App\Helper\Reply;
use App\Http\Requests\Invoices\StoreInvoice;
use App\Http\Requests\Invoices\StoreRecurringInvoice;
use App\Invoice;
use App\InvoiceItems;
use App\InvoiceSetting;
use App\Notifications\NewInvoice;
use App\Product;
use App\Project;
use App\RecurringInvoice;
use App\RecurringInvoiceItems;
use App\Tax;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ManageInvoicesRecurringController extends AdminBaseController
{

    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.recurringInvoices';
        $this->pageIcon = 'layers';
        $this->middleware(function ($request, $next) {
            if (!in_array('invoices',$this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(InvoiceRecurringDataTable $dataTable)
    {
        $this->projects = Project::all();
        $this->clients = User::allClients();
        return $dataTable->render('admin.invoice-recurring.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->projects = Project::all();
        $this->currencies = Currency::all();
        $this->lastInvoice = Invoice::lastInvoiceNumber() + 1;
        $this->invoiceSetting = InvoiceSetting::first();
        $this->zero = '';
        if (strlen($this->lastInvoice) < $this->invoiceSetting->invoice_digit) {
            for ($i = 0; $i < $this->invoiceSetting->invoice_digit - strlen($this->lastInvoice); $i++) {
                $this->zero = '0' . $this->zero;
            }
        }

        $this->taxes = Tax::all();
        $this->products = Product::select('id', 'name as title', 'name as text')->get();
        $this->clients = User::allClients();

        return view('admin.invoice-recurring.create', $this->data);
    }

    /**
     * @param StoreRecurringInvoice $request
     * @return array
     */
    public function store(StoreRecurringInvoice $request)
    {

        $items = $request->input('item_name');
        $itemsSummary = $request->input('item_summary');
        $cost_per_item = $request->input('cost_per_item');
        $quantity = $request->input('quantity');
        $hsnSacCode = request()->input('hsn_sac_code');
        $amount = $request->input('amount');
        $tax = $request->input('taxes');

        foreach ($quantity as $qty) {
            if (!is_numeric($qty) && (intval($qty) < 1)) {
                return Reply::error(__('messages.quantityNumber'));
            }
        }

        foreach ($cost_per_item as $rate) {
            if (!is_numeric($rate)) {
                return Reply::error(__('messages.unitPriceNumber'));
            }
        }

        foreach ($amount as $amt) {
            if (!is_numeric($amt)) {
                return Reply::error(__('messages.amountNumber'));
            }
        }

        foreach ($items as $itm) {
            if (is_null($itm)) {
                return Reply::error(__('messages.itemBlank'));
            }
        }

        $invoice = new RecurringInvoice();
        $invoice->project_id          = $request->project_id ?? null;
        $invoice->client_id             = $request->project_id == '' && $request->has('client_id') ? $request->client_id : null;
        $invoice->issue_date          = Carbon::createFromFormat($this->global->date_format, $request->issue_date)->format('Y-m-d');
        $invoice->due_date            = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');
        $invoice->sub_total           = $request->sub_total;
        $invoice->total               = $request->total;
        $invoice->discount            = round($request->discount_value, 2);
        $invoice->discount_type       = $request->discount_type;
        $invoice->total               = round($request->total, 2);
        $invoice->currency_id         = $request->currency_id;
        $invoice->note                = $request->note;

        $invoice->rotation            = $request->rotation;
        $invoice->billing_cycle       = $request->billing_cycle > 0 ? $request->billing_cycle : null;
        $invoice->unlimited_recurring = $request->billing_cycle < 0  ? 1 : 0;
        $invoice->created_by          = $this->user->id;

        if($request->rotation == 'weekly' || $request->rotation == 'bi-weekly'){
            $invoice->day_of_week = $request->day_of_week;
        }
        elseif ($request->rotation == 'monthly' || $request->rotation == 'quarterly' || $request->rotation == 'half-yearly' || $request->rotation == 'annually'){
            $invoice->day_of_month = $request->day_of_month;
        }

        if ($request->project_id > 0) {
            $invoice->project_id = $request->project_id;
        }

        $invoice->client_can_stop = ($request->client_can_stop) ? 1 : 0;

        $invoice->status = 'active';
        $invoice->save();

        return Reply::redirect(route('admin.invoice-recurring.index'), __('messages.recurringInvoiceCreated'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->invoice = RecurringInvoice::with('recurrings')->findOrFail($id);

        if ($this->invoice->discount > 0) {
            if ($this->invoice->discount_type == 'percent') {
                $this->discount = (($this->invoice->discount / 100) * $this->invoice->sub_total);
            } else {
                $this->discount = $this->invoice->discount;
            }
        } else {
            $this->discount = 0;
        }

        $taxList = array();

        $items = RecurringInvoiceItems::whereNotNull('taxes')
            ->where('invoice_recurring_id', $this->invoice->id)
            ->get();
        foreach ($items as $item) {
            if ($this->invoice->discount > 0 && $this->invoice->discount_type == 'percent') {
                $item->amount = $item->amount - (($this->invoice->discount / 100) * $item->amount);
            }
            foreach (json_decode($item->taxes) as $tax) {
                $this->tax = InvoiceItems::taxbyid($tax)->first();
                if (!isset($taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'])) {
                    $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = ($this->tax->rate_percent / 100) * $item->amount;
                } else {
                    $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] + (($this->tax->rate_percent / 100) * $item->amount);
                }
            }
        }

        $this->taxes = $taxList;

        $this->settings = $this->company;
        $this->invoiceSetting = InvoiceSetting::first();

        return view('admin.invoice-recurring.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->invoice = RecurringInvoice::findOrFail($id);
        $this->projects = Project::all();
        $this->currencies = Currency::all();

        if ($this->invoice->status == 'paid') {
            abort(403);
        }
        $this->taxes = Tax::all();
        $this->products = Product::select('id', 'name as title', 'name as text')->get();
        $this->clients = User::allClients();
        if ($this->invoice->project_id != '') {
            $companyName = Project::where('id', $this->invoice->project_id)->with('clientdetails')->first();
            $this->companyName = $companyName->clientdetails ? $companyName->clientdetails->company_name : '';
            $this->clientId = $companyName->clientdetails ? $companyName->clientdetails->user_id : '';
        }

        return view('admin.invoice-recurring.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $items         = $request->input('item_name');
        $itemsSummary  = $request->input('item_summary');
        $cost_per_item = $request->input('cost_per_item');
        $quantity      = $request->input('quantity');
        $hsnSacCode    = request()->input('hsn_sac_code');
        $amount        = $request->input('amount');
        $tax           = $request->input('taxes');

        foreach ($quantity as $qty) {
            if (!is_numeric($qty) && (intval($qty) < 1)) {
                return Reply::error(__('messages.quantityNumber'));
            }
        }

        foreach ($cost_per_item as $rate) {
            if (!is_numeric($rate)) {
                return Reply::error(__('messages.unitPriceNumber'));
            }
        }

        foreach ($amount as $amt) {
            if (!is_numeric($amt)) {
                return Reply::error(__('messages.amountNumber'));
            }
        }

        foreach ($items as $itm) {
            if (is_null($itm)) {
                return Reply::error(__('messages.itemBlank'));
            }
        }

        $invoice =  RecurringInvoice::findOrFail($id);
        $invoice->project_id          = $request->project_id ?? null;
        $invoice->client_id           = $request->project_id == '' && $request->has('client_id') ? $request->client_id : null;
        $invoice->issue_date          = Carbon::createFromFormat($this->global->date_format, $request->issue_date)->format('Y-m-d');
        $invoice->due_date            = Carbon::createFromFormat($this->global->date_format, $request->due_date)->format('Y-m-d');
        $invoice->sub_total           = $request->sub_total;
        $invoice->total               = $request->total;
        $invoice->discount            = round($request->discount_value, 2);
        $invoice->discount_type       = $request->discount_type;
        $invoice->total               = round($request->total, 2);
        $invoice->currency_id         = $request->currency_id;
        $invoice->note                = $request->note;

        $invoice->rotation            = $request->rotation;
        $invoice->billing_cycle       = $request->billing_cycle > 0 ? $request->billing_cycle : null;
        $invoice->unlimited_recurring = $request->billing_cycle < 0  ? 1 : 0;
        $invoice->created_by          = $this->user->id;

        if($request->rotation == 'weekly' || $request->rotation == 'bi-weekly'){
            $invoice->day_of_week = $request->day_of_week;
        }
        elseif ($request->rotation == 'monthly' || $request->rotation == 'quarterly' || $request->rotation == 'half-yearly' || $request->rotation == 'annually'){
            $invoice->day_of_month = $request->day_of_month;
        }

        if ($request->project_id > 0) {
            $invoice->project_id = $request->project_id;
        }

        $invoice->client_can_stop = ($request->client_can_stop) ? 1 : 0;

        $invoice->status = $request->status;
        $invoice->save();

        // delete and create new
        RecurringInvoiceItems::where('invoice_recurring_id', $invoice->id)->delete();

        foreach ($items as $key => $item) :
            RecurringInvoiceItems::create(
                [
                    'invoice_recurring_id' => $invoice->id,
                    'item_name'            => $item,
                    'hsn_sac_code'         => $hsnSacCode[$key],
                    'item_summary'         => $itemsSummary[$key],
                    'type'                 => 'item',
                    'quantity'             => $quantity[$key],
                    'unit_price'           => round($cost_per_item[$key], 2),
                    'amount'               => round($amount[$key], 2),
                    'taxes'                => $tax ? array_key_exists($key, $tax) ? json_encode($tax[$key]) : null : null
                ]
            );
        endforeach;

        return Reply::redirect(route('admin.invoice-recurring.index'), __('messages.recurringInvoiceCreated'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        RecurringInvoice::destroy($id);
        return Reply::success(__('messages.invoiceDeleted'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function changeStatus(Request $request)
    {
        $invoiceId = $request->invoiceId;
        $status = $request->status;
        $invoice = RecurringInvoice::findOrFail($invoiceId);
        $invoice->status = $status;
        $invoice->save();
        return Reply::success(__('messages.updateSuccess'));
    }

    /**
     * @param RecurringInvoicesDataTable $dataTable
     * @param $id
     * @return mixed
     */
    public function recurringInvoices(RecurringInvoicesDataTable $dataTable, $id)
    {
        $this->invoice = RecurringInvoice::findOrFail($id);
        $this->projects = Project::all();
        $this->clients = User::allClients();
        return $dataTable->render('admin.invoice-recurring.recurring-invoices', $this->data);
    }
}

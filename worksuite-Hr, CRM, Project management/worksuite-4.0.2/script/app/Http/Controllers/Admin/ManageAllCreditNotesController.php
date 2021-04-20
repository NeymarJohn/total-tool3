<?php

namespace App\Http\Controllers\Admin;

use App\CreditNoteItem;
use App\Currency;
use App\DataTables\Admin\AllCreditNotesDataTable;
use App\Helper\Reply;
use App\Http\Requests\CreditNotes\creditNoteFileStore;
use App\Http\Requests\CreditNotes\StoreCreditNotes;
use App\Http\Requests\CreditNotes\UpdateCreditNote;
use App\CreditNotes;
use App\Invoice;
use App\InvoiceSetting;
use App\Payment;
use App\Product;
use App\Project;
use App\Setting;
use App\Tax;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ManageAllCreditNotesController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.credit-note';
        $this->pageIcon = 'ti-receipt';
        $this->middleware(function ($request, $next) {
            if (!in_array('invoices', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(AllCreditNotesDataTable $dataTable)
    {
        if (!request()->ajax()) {
            $this->projects = Project::allProjects();
        }
        $this->clients = User::allClients();
        return $dataTable->render('admin.credit-notes.index', $this->data);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function data(Request $request)
    {
        $firstCreditNotes = CreditNotes::orderBy('id', 'desc')->first();
        $creditNotes = CreditNotes::with(['project:id,project_name,client_id', 'currency:id,currency_symbol,currency_code', 'invoice'])
            ->select('id', 'project_id', 'invoice_id', 'currency_id', 'cn_number', 'total', 'issue_date', 'status');

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $creditNotes = $creditNotes->where(DB::raw('DATE(credit_notes.`issue_date`)'), '>=', $request->startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $creditNotes = $creditNotes->where(DB::raw('DATE(credit_notes.`issue_date`)'), '<=', $request->endDate);
        }

        if ($request->projectID != 'all' && !is_null($request->projectID)) {
            $creditNotes = $creditNotes->where('credit_notes.project_id', '=', $request->projectID);
        }

        $creditNotes = $creditNotes->orderBy('credit_notes.id', 'desc')->get();

        return DataTables::of($creditNotes)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($firstCreditNotes) {
                $action = '<div class="btn-group dropdown m-r-10">
                <button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle waves-effect waves-light" type="button"><i class="fa fa-gears "></i></button>
                <ul role="menu" class="dropdown-menu">
                    <li><a href="' . route("admin.all-credit-notes.download", $row->id) . '"><i class="fa fa-download"></i> ' . __('app.download') . '</a></li>';

                $action .= ' <li><a href="javascript:" data-credit-notes-id="' . $row->id . '" class="credit-notes-upload" data-toggle="modal" data-target="#creditNoteUploadModal"><i class="fa fa-upload"></i> ' . __('app.upload') . ' </a></li>';

                if ($row->status == 'open') {
                    $action .= '<li><a href="' . route("admin.all-credit-notes.edit", $row->id) . '"><i class="fa fa-pencil"></i> ' . __('app.edit') . '</a></li>';
                }

                if ($firstCreditNotes->id == $row->id) {
                    $action .= '<li><a href="javascript:;" data-toggle="tooltip"  data-credit-notes-id="' . $row->id . '" class="sa-params"><i class="fa fa-times"></i> ' . __('app.delete') . '</a></li>';
                }
                $action .= '</ul>
              </div>
              ';

                return $action;
            })
            ->editColumn('project_name', function ($row) {
                return '<a href="' . route('admin.projects.show', $row->project_id) . '">' . ucfirst($row->project->project_name) . '</a>';
            })
            ->editColumn('cn_number', function ($row) {
                return '<a href="' . route('admin.all-credit-notes.show', $row->id) . '">' . ucfirst($row->cn_number) . '</a>';
            })
            ->editColumn('invoice_number', function ($row) {
                return $row->invoice ? ucfirst($row->invoice->invoice_number) : '--';
            })
            ->editColumn('total', function ($row) {
                $currencyCode = ' (' . $row->currency->currency_code . ') ';
                $currencySymbol = $row->currency->currency_symbol;

                return '<div class="text-right">'.__('app.total').': ' . $currencySymbol . $row->total . '<br>'.__('app.used').': ' . $currencySymbol . $row->creditAmountUsed() . '<br>'.__('app.remaining').': ' . $currencySymbol . $row->creditAmountRemaining() . '</div>';
            })
            ->editColumn(
                'issue_date',
                function ($row) {
                    return $row->issue_date->timezone($this->global->timezone)->format($this->global->date_format);
                }
            )
            ->editColumn('status', function ($row) {
                if ($row->status == 'open') {
                    return '<label class="label label-success">' . strtoupper($row->status) . '</label>';
                } else {
                    return '<label class="label label-danger">' . strtoupper($row->status) . '</label>';
                }
            })
            ->rawColumns(['project_name', 'action', 'cn_number', 'invoice_number', 'status', 'total'])
            ->removeColumn('currency_symbol')
            ->removeColumn('currency_code')
            ->removeColumn('project_id')
            ->make(true);
    }

    public function download($id)
    {
        //        header('Content-type: application/pdf');

        $this->creditNote = CreditNotes::findOrFail($id);
        $this->invoiceNumber = 0;
        if (Invoice::where('id', '=', $this->creditNote->invoice_id)->exists()) {
            $this->invoiceNumber = Invoice::select('invoice_number')->where('id', $this->creditNote->invoice_id)->first();
        }
        // Download file uploaded
        if ($this->creditNote->file != null) {
            return response()->download(storage_path('app/public/credit-note-files') . '/' . $this->creditNote->file);
        }

        if ($this->creditNote->discount > 0) {
            if ($this->creditNote->discount_type == 'percent') {
                $this->creditNote = (($this->creditNote->discount / 100) * $this->creditNote->sub_total);
            } else {
                $this->discount = $this->creditNote->discount;
            }
        } else {
            $this->discount = 0;
        }

        $taxList = array();

        $items = CreditNoteItem::whereNotNull('taxes')
            ->where('credit_note_id', $this->creditNote->id)
            ->get();
        foreach ($items as $item) {
            foreach (json_decode($item->taxes) as $tax) {
                $this->tax = CreditNoteItem::taxbyid($tax)->first();
                if ($this->tax) {
                    if (!isset($taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'])) {
                        $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = ($this->tax->rate_percent / 100) * $item->amount;
                    } else {
                        $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] + (($this->tax->rate_percent / 100) * $item->amount);
                    }
                }
            }
        }

        $this->taxes = $taxList;

        $this->settings = cache()->remember(
            'global-setting', 60*60*24, function () {
                return \App\Setting::first();
            }
        );

        $this->creditNoteSetting = invoice_setting();

        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        App::setLocale($this->invoiceSetting->locale);
        Carbon::setLocale($this->invoiceSetting->locale);
        $pdf->loadView('credit-notes.' . $this->creditNoteSetting->template, $this->data);

        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(530, 820, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        $filename = $this->creditNote->cn_number;
        //       return $pdf->stream();
        return $pdf->download($filename . '.pdf');
    }

    public function destroy($id)
    {
        $firstCreditNote = CreditNotes::orderBy('id', 'desc')->first();

        if ($firstCreditNote->id == $id) {
            $creditNote = CreditNotes::find($id);
            if (Invoice::where('id', '=', $creditNote->invoice_id)->exists()) {
                Invoice::where('id', '=', $creditNote->invoice_id)->update(['credit_note' => 0]);
            }
            $invoices = $creditNote->invoices()->get();
            $creditNote->invoices()->detach();

            foreach ($invoices as $invoice) {
                // change invoice status
                $invoice->status = 'partial';
                if ($invoice->amountPaid() == $invoice->total) {
                    $invoice->status = 'paid';
                }
                if ($invoice->amountPaid() == 0) {
                    $invoice->status = 'unpaid';
                }
                $invoice->save();
            }

            CreditNotes::destroy($id);
            return Reply::success(__('messages.creditNoteDeleted'));
        } else {
            return Reply::error(__('messages.creditNoteCanNotDeleted'));
        }
    }

    public function create()
    {
        abort(404);
    }

    public function store(StoreCreditNotes $request)
    {
        $items = $request->input('item_name');
        $cost_per_item = $request->input('cost_per_item');
        $quantity = $request->input('quantity');
        $hsnSacCode = request()->input('hsn_sac_code');
        $amount = $request->input('amount');
        $amountArray = $request->input('amount');
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
        DB::beginTransaction();

        $invoice = Invoice::find($request->invoice_id);

        $clientId = null;
        if($invoice->client_id){
            $clientId = $invoice->client_id;
        }
        elseif(!is_null($invoice->project) && $invoice->project->client_id){
            $clientId = $invoice->project->client_id;
        }

        $creditNote = new CreditNotes();

        $creditNote->project_id         = ($invoice->project_id) ? $invoice->project_id : null ;
        $creditNote->client_id          = $clientId;
        $creditNote->cn_number          = CreditNotes::count() + 1;
        $creditNote->invoice_id         = $invoice->id;
        $creditNote->issue_date         = Carbon::parse($request->issue_date)->format('Y-m-d');
        $creditNote->due_date           = Carbon::parse($request->due_date)->format('Y-m-d');
        $creditNote->sub_total          = round($request->sub_total, 2);
        $creditNote->discount           = round($request->discount_value, 2);
        $creditNote->discount_type      = $request->discount_type;
        $creditNote->total              = round($request->total, 2);
        $creditNote->currency_id        = $request->currency_id;
        $creditNote->recurring          = $request->recurring_payment;
        $creditNote->billing_frequency  = $request->recurring_payment == 'yes' ? $request->billing_frequency : null;
        $creditNote->billing_interval   = $request->recurring_payment == 'yes' ? $request->billing_interval : null;
        $creditNote->billing_cycle      = $request->recurring_payment == 'yes' ? $request->billing_cycle : null;
        $creditNote->note               = $request->note;
        $creditNote->save();

        if ($request->invoice_id) {
            $invoice = Invoice::findOrFail($request->invoice_id);

            $invoice->credit_note = 1;

            if ($invoice->status != 'paid') {
                $amount = round($invoice->total, 2);

                if (round($request->total, 2) > round($invoice->total - $invoice->getPaidAmount(), 2)) {
                    // create payment for invoice total
                    if ($invoice->status == 'partial') {
                        $amount = round($invoice->total - $invoice->getPaidAmount(), 2);
                    }

                    $invoice->status = 'paid';
                } else {
                    $amount = round($request->total, 2);
                    $invoice->status = 'partial';
                    $creditNote->status = 'closed';

                    if (round($request->total, 2) == round($invoice->total - $invoice->getPaidAmount(), 2)) {
                        if ($invoice->status == 'partial') {
                            $amount = round($invoice->total - $invoice->getPaidAmount(), 2);
                        }

                        $invoice->status = 'paid';
                    }
                }
                $creditNote->invoices()
                    ->attach($invoice->id, [
                        'credit_amount' => $amount,
                        'date' => Carbon::now()
                    ]);
                $creditNote->save();
            }
            $invoice->save();
        }

        DB::commit();
        foreach ($items as $key => $item) :
            if (!is_null($item)) {
                CreditNoteItem::create([
                    'credit_note_id' => $creditNote->id,
                    'item_name' => $item,
                    'hsn_sac_code' => (isset($hsnSacCode[$key]) && !is_null($hsnSacCode[$key])) ? $hsnSacCode[$key] : null,
                    'type' => 'item',
                    'quantity' => $quantity[$key],
                    'unit_price' => round($cost_per_item[$key], 2),
                    'amount' => round($amountArray[$key], 2),
                    'taxes' => $tax ? array_key_exists($key, $tax) ? json_encode($tax[$key]) : null : null
                ]);
            }
        endforeach;

        //log search
        $this->logSearchEntry($creditNote->id, 'CreditNote ' . $creditNote->cn_number, 'admin.all-credit-notes.show', 'creditNote');

        return Reply::redirect(route('admin.all-credit-notes.index'), __('messages.creditNoteCreated'));
    }

    public function edit($id)
    {
        $this->creditNote = CreditNotes::findOrFail($id);
        $this->projects = Project::allProjects();
        $this->currencies = Currency::all();
        $this->taxes = Tax::all();
        $this->products = Product::select('id', 'name as title', 'name as text')->get();

        return view('admin.credit-notes.edit', $this->data);
    }

    public function update(UpdateCreditNote $request, $id)
    {
        $items = $request->input('item_name');
        $cost_per_item = $request->input('cost_per_item');
        $quantity = $request->input('quantity');
        $hsnSacCode = request()->input('hsn_sac_code');
        $amount = $request->input('amount');

        $tax = $request->input('taxes');

        foreach ($quantity as $qty) {
            if (!is_numeric($qty) && $qty < 1) {
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

        $creditNote = CreditNotes::findOrFail($id);

        $creditNote->project_id = $request->project_id;
        $creditNote->issue_date = Carbon::parse($request->issue_date)->format('Y-m-d');
        $creditNote->due_date = Carbon::parse($request->due_date)->format('Y-m-d');
        $creditNote->sub_total = round($request->sub_total, 2);
        $creditNote->discount = round($request->discount_value, 2);
        $creditNote->discount_type = $request->discount_type;
        $creditNote->total = round($request->total, 2);
        $creditNote->currency_id = $request->currency_id;
        $creditNote->recurring = $request->recurring_payment;
        $creditNote->billing_frequency = $request->recurring_payment == 'yes' ? $request->billing_frequency : null;
        $creditNote->billing_interval = $request->recurring_payment == 'yes' ? $request->billing_interval : null;
        $creditNote->billing_cycle = $request->recurring_payment == 'yes' ? $request->billing_cycle : null;
        $creditNote->note = $request->note;
        $creditNote->save();

        // delete and create new
        CreditNoteItem::where('credit_note_id', $creditNote->id)->delete();

        foreach ($items as $key => $item) :
            CreditNoteItem::create(['credit_note_id' => $creditNote->id, 'item_name' => $item, 'hsn_sac_code' => (isset($hsnSacCode[$key]) && !is_null($hsnSacCode[$key])) ? $hsnSacCode[$key] : null, 'type' => 'item', 'quantity' => $quantity[$key], 'unit_price' => round($cost_per_item[$key], 2), 'amount' => round($amount[$key], 2), 'taxes' => $tax ? array_key_exists($key, $tax) ? json_encode($tax[$key]) : null : null]);
        endforeach;

        return Reply::redirect(route('admin.all-credit-notes.index'), __('messages.updateSuccess'));
    }

    public function show($id)
    {
        $this->creditNote = CreditNotes::findOrFail($id);
        $this->paidAmount = $this->creditNote->getPaidAmount();

        if ($this->creditNote->discount > 0) {
            if ($this->creditNote->discount_type == 'percent') {
                $this->discount = (($this->creditNote->discount / 100) * $this->creditNote->sub_total);
            } else {
                $this->discount = $this->creditNote->discount;
            }
        } else {
            $this->discount = 0;
        }
        $this->invoiceExist = false;
        if (Invoice::where('id', '=', $this->creditNote->invoice_id)->exists()) {
            $this->invoiceExist = true;
        }

        $taxList = array();

        $items = CreditNoteItem::whereNotNull('taxes')
            ->where('credit_note_id', $this->creditNote->id)
            ->get();
        foreach ($items as $item) {
            foreach (json_decode($item->taxes) as $tax) {
                $this->tax = CreditNoteItem::taxbyid($tax)->first();
                if ($this->tax) {
                    if (!isset($taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'])) {
                        $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = ($this->tax->rate_percent / 100) * $item->amount;
                    } else {
                        $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] + (($this->tax->rate_percent / 100) * $item->amount);
                    }
                }
            }
        }

        $this->taxes = $taxList;

        $this->settings = $this->global;
        $this->creditNoteSetting = invoice_setting();
        return view('admin.credit-notes.show', $this->data);
    }

    public function applyToInvoiceModal($id)
    {
        $this->creditNote = CreditNotes::findOrFail($id);
        $this->nonPaidInvoices = Invoice::where('status', '<>', 'paid')->where('credit_note', 0);

        if ($this->creditNote->project_id) {
            $this->nonPaidInvoices = $this->nonPaidInvoices->where('project_id', $this->creditNote->project_id);
        }
        $this->nonPaidInvoices = $this->nonPaidInvoices->with('payment')->get();

        return view('admin.credit-notes.apply_to_invoices', $this->data);
    }

    public function applyToInvoice(Request $request, $id)
    {
        $totalCreditAmount = 0.00;

        foreach ($request->invoices as $invoice) {
            $totalCreditAmount += $invoice['value'];
        }

        if ($totalCreditAmount == 0) {
            return Reply::error(__('messages.pleaseEnterCreditAmount'));
        }

        $creditNote = CreditNotes::findOrFail($id);
        $creditTotalAmount = 0.00;

        if ((float) $request->remainingAmount <= 0) {
            $creditNote->status = 'closed';
        }

        foreach ($request->invoices as $invoice) {
            if ($invoice['value'] !== '0') {
                $creditTotalAmount += (float) $invoice['value'];

                $creditNote->invoices()
                    ->attach($invoice['invoiceId'], [
                        'credit_amount' => $invoice['value'],
                        'date' => Carbon::now()
                    ]);

                $reqInvoice = Invoice::findOrFail($invoice['invoiceId']);

                $reqInvoice->status = 'paid';

                if ($reqInvoice->total > $reqInvoice->amountPaid()) {
                    $reqInvoice->status = 'partial';
                }
                $dueAmount = $reqInvoice->amountDue();
                $reqInvoice->due_amount = $dueAmount;
                $reqInvoice->save();
            }
        }

        $creditNote->save();

        return Reply::redirect(route('admin.all-credit-notes.show', $creditNote->id), __('messages.creditNoteAppliedSuccessfully'));
    }

    public function creditedInvoices(Request $request, $id)
    {
        $this->creditNote = CreditNotes::findOrFail($id);

        $this->invoices = $this->creditNote->invoices()->orderBy('date', 'DESC')->get();

        return view('admin.credit-notes.credited_invoices', $this->data);
    }

    public function deleteCreditedInvoice(Request $request, $id)
    {
        $this->creditNote = CreditNotes::findOrFail($request->credit_id);

        // delete from credit_notes_invoice_table
        $creditNoteInvoice = $this->creditNote->invoices()->wherePivot('id', $id);
        $invoice = $creditNoteInvoice->first();
        $creditNoteInvoice->detach();

        // change invoice status
        $invoice->status = 'partial';
        if ($invoice->amountPaid() == $invoice->total) {
            $invoice->status = 'paid';
        }
        if ($invoice->amountPaid() == 0) {
            $invoice->status = 'unpaid';
        }
        $invoice->save();

        // change credit status
        if ($this->creditNote->status == 'closed') {
            $this->creditNote->status = 'open';
            $this->creditNote->save();
        }

        $this->invoices = $this->creditNote->invoices()->orderBy('date', 'DESC')->get();
        if ($this->invoices->count() > 0) {
            $view = view('admin.credit-notes.credited_invoices', $this->data)->render();

            return Reply::successWithData(__('messages.creditedInvoiceDeletedSuccessfully'), ['view' => $view]);
        }
        return Reply::redirect(route('admin.all-credit-notes.show', [$this->creditNote->id]), __('messages.creditedInvoiceDeletedSuccessfully'));
    }

    public function convertInvoice($id)
    {
        $this->invoiceId = $id;
        $this->creditNote = Invoice::with(['items', 'project', 'client'])->findOrFail($id);
        $this->lastCreditNote = CreditNotes::count() + 1;
        $this->creditNoteSetting = invoice_setting();
        $this->projects = Project::allProjects();
        $this->currencies = Currency::all();
        $this->taxes = Tax::all();
        $this->products = Product::select('id', 'name as title', 'name as text')->get();
        $this->zero = '';
        if (strlen($this->lastCreditNote) < $this->creditNoteSetting->credit_note_digit) {
            for ($i = 0; $i < $this->creditNoteSetting->credit_note_digit - strlen($this->lastCreditNote); $i++) {
                $this->zero = '0' . $this->zero;
            }
        }

        $items = $this->creditNote->items->filter(function ($value, $key) {
            return $value->type == 'item';
        });

        $tax = $this->creditNote->items->filter(function ($value, $key) {
            return $value->type == 'tax';
        });
        //        dd($items);

        $this->totalTax = $tax->sum('amount');
        $this->discount = $this->creditNote->discount;
        $this->discountType = $this->creditNote->discount_type;

        if ($this->discountType == 'percent') {
            $this->totalDiscount = $items->sum('amount') * $this->discount / 100;
        }

        if ($this->discountType == 'fixed') {
            $this->totalDiscount =  $this->discount;
        }

        return view('admin.credit-notes.convert_invoice', $this->data);
    }

    public function addItems(Request $request)
    {
        $this->items = Product::with('tax')->find($request->id);
        $exchangeRate = Currency::find($request->currencyId);

        if (!is_null($exchangeRate) && !is_null($exchangeRate->exchange_rate)) {
            if ($this->items->total_amount != "") {
                $this->items->price = floor($this->items->total_amount * $exchangeRate->exchange_rate);
            } else {
                $this->items->price = $this->items->price * $exchangeRate->exchange_rate;
            }
        } else {
            if ($this->items->total_amount != "") {
                $this->items->price = $this->items->total_amount;
            }
        }
        $this->items->price =  number_format((float)$this->items->price, 2, '.', '');
        $this->taxes = Tax::all();
        $view = view('admin.credit-notes.add-item', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function paymentDetail($creditNoteID)
    {
        $this->creditNote = CreditNotes::findOrFail($creditNoteID);

        return View::make('admin.credit-notes.payment-detail', $this->data);
    }

    /**
     * @param InvoiceFileStore $request
     * @return array
     */
    public function storeFile(creditNoteFileStore $request)
    {
        $creditNoteId = $request->credit_note_id;
        $file = $request->file('file');

        $newName = $file->hashName(); // setting hashName name
        // Getting invoice data
        $creditNote = CreditNotes::find($creditNoteId);

        if ($creditNote != null) {

            if ($creditNote->file != null) {
                unlink(storage_path('app/public/credit-note-files') . '/' . $creditNote->file);
            }

            $file->move(storage_path('app/public/credit-note-files'), $newName);

            $creditNote->file = $newName;
            $creditNote->file_original_name = $file->getClientOriginalName(); // Getting uploading file name;

            $creditNote->save();

            return Reply::success(__('messages.fileUploadedSuccessfully'));
        }

        return Reply::error(__('messages.fileUploadIssue'));
    }

    /**
     * @param Request $request
     * @return array
     */
    public function destroyFile(Request $request)
    {
        $creditNoteId = $request->credit_note_id;

        $creditNote = CreditNotes::find($creditNoteId);

        if ($creditNote != null) {

            if ($creditNote->file != null) {
                unlink(storage_path('app/public/credit-note-files') . '/' . $creditNote->file);
            }

            $creditNote->file = null;
            $creditNote->file_original_name = null;

            $creditNote->save();
        }

        return Reply::success(__('messages.fileDeleted'));
    }

    /**
     * @param $startDate
     * @param $endDate
     * @param $status
     * @param $projectID
     */
    public function export($startDate, $endDate, $projectID)
    {
        $creditNote = CreditNotes::with(['project:id,project_name', 'currency:id,currency_symbol']);

        if ($startDate !== null && $startDate != 'null' && $startDate != '') {
            $creditNote = $creditNote->where(DB::raw('DATE(credit_notes.`issue_date`)'), '>=', $startDate);
        }

        if ($endDate !== null && $endDate != 'null' && $endDate != '') {
            $creditNote = $creditNote->where(DB::raw('DATE(credit_notes.`issue_date`)'), '<=', $endDate);
        }

        if ($projectID != 'all' && !is_null($projectID)) {
            $creditNote = $creditNote->where('credit_notes.project_id', '=', $projectID);
        }

        $creditNotes = $creditNote->latest()
            ->get()
            ->map(function ($cn) {
                return [
                    'id' => $cn->id,
                    'cn_number' => $cn->cn_number,
                    'project_name' => $cn->project->project_name,
                    'status' => ucfirst($cn->status),
                    'total' => $cn->currency->currency_symbol . $cn->total,
                    'credit_amount_used' => $cn->currency->currency_symbol . $cn->creditAmountUsed(),
                    'credit_amount_remaining' => $cn->currency->currency_symbol . $cn->creditAmountRemaining(),
                    'issue_date' => $cn->issue_date ? $cn->issue_date->format($this->global->date_format) : ''
                ];
            })->toArray();

        // Define the Excel spreadsheet headers
        $headerRow = ['ID', 'CreditNote #', 'Project Name', 'Status', 'Total Amount', 'Credit Amount Used', 'Credit Amount Remaining', 'Invoice Date'];

        array_unshift($creditNotes, $headerRow);


        // Generate and return the spreadsheet
        Excel::create('creditNote', function ($excel) use ($creditNotes) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Credit Note');
            $excel->setCreator('Worksuite')->setCompany($this->companyName);
            $excel->setDescription('Credit Note file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($creditNotes) {
                $sheet->fromArray($creditNotes, null, 'A1', false, false);

                $sheet->row(1, function ($row) {

                    // call row manipulation methods
                    $row->setFont(array(
                        'bold'       =>  true
                    ));
                });
            });
        })->download('xlsx');
    }

    public function domPdfObjectForDownload($id)
    {
        $this->creditNote = CreditNotes::findOrFail($id);
        $this->invoiceNumber = 0;
        if (Invoice::where('id', '=', $this->creditNote->invoice_id)->exists()) {
            $this->invoiceNumber = Invoice::select('invoice_number')->where('id', $this->creditNote->invoice_id)->first();
        }
        // Download file uploaded
        if ($this->creditNote->file != null) {
            return response()->download(storage_path('app/public/credit-note-files') . '/' . $this->creditNote->file);
        }

        if ($this->creditNote->discount > 0) {
            if ($this->creditNote->discount_type == 'percent') {
                $this->discount = (($this->creditNote->discount / 100) * $this->creditNote->sub_total);
            } else {
                $this->discount = $this->creditNote->discount;
            }
        } else {
            $this->discount = 0;
        }

        $taxList = array();

        $items = CreditNoteItem::whereNotNull('taxes')
            ->where('credit_note_id', $this->creditNote->id)
            ->get();
        foreach ($items as $item) {
            foreach (json_decode($item->taxes) as $tax) {
                $this->tax = CreditNoteItem::taxbyid($tax)->first();
                if ($this->tax) {
                    if (!isset($taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'])) {
                        $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = ($this->tax->rate_percent / 100) * $item->amount;
                    } else {
                        $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] + (($this->tax->rate_percent / 100) * $item->amount);
                    }
                }
            }
        }

        $this->taxes = $taxList;

        $this->settings = cache()->remember(
            'global-setting', 60*60*24, function () {
            return \App\Setting::first();
        }
        );

        $this->creditNoteSetting = invoice_setting();

        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        App::setLocale($this->invoiceSetting->locale);
        Carbon::setLocale($this->invoiceSetting->locale);
        $pdf->loadView('credit-notes.' . $this->creditNoteSetting->template, $this->data);

        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(530, 820, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        $filename = $this->creditNote->cn_number;
        //       return $pdf->stream();
        return [
            'pdf' => $pdf,
            'fileName' => $filename
        ];
    }
}

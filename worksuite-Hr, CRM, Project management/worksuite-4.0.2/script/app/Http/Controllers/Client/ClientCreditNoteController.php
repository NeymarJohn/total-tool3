<?php

namespace App\Http\Controllers\Client;

use App\CreditNoteItem;
use App\CreditNotes;
use App\Invoice;
use App\InvoiceSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Yajra\DataTables\Facades\DataTables;

class ClientCreditNoteController extends ClientBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.credit-note';
        $this->pageIcon = 'ti-receipt';

        $this->middleware(function ($request, $next) {
            if (!in_array('invoices', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        return view('client.credit-note.index', $this->data);
    }

    public function create()
    {
        $invoiceSettings = InvoiceSetting::select('invoice_prefix', 'invoice_digit')->first();

        $invoices = CreditNotes::leftJoin('projects', 'projects.id', '=', 'credit_notes.project_id')
            ->leftJoin('users', 'users.id', '=', 'credit_notes.client_id')
            ->leftJoin('invoices', 'invoices.id', '=', 'credit_notes.invoice_id')
            ->join('currencies', 'currencies.id', '=', 'invoices.currency_id')
            ->select('credit_notes.id', 'projects.project_name', 'credit_notes.cn_number','invoices.invoice_number','invoices.id as invoiceid', 'currencies.currency_symbol', 'currencies.currency_code', 'credit_notes.total', 'credit_notes.issue_date', 'credit_notes.status')
//            ->where(function ($query) {
//                $query->where('invoices.client_id', $this->user->id)
//                    ->orWhere('credit_notes.client_id', $this->user->id);
//                    ->orWhere('projects.client_id', $this->user->id);
//            })
            ->Where('credit_notes.client_id', $this->user->id);

        return DataTables::of($invoices)
            ->addColumn('action', function ($row) {
                return '<a href="' . route('client.credit-notes.download', $row->id) . '" data-toggle="tooltip" data-original-title="Download" class="btn  btn-sm btn-outline btn-info"><i class="fa fa-download"></i> '.__('app.download').'</a>';
            })
            ->editColumn('project_name', function ($row) {
                return $row->project_name != '' ? $row->project_name : '--';
            })
            ->editColumn('cn_number', function ($row) {
                return '<a style="text-decoration: underline" href="' . route('client.credit-notes.show', $row->id) . '">' . $row->cn_number . '</a>';
            })
            ->editColumn('invoice_number', function ($row) use($invoiceSettings) {
                $zero = '';
                if (!is_null($row->invoice_number)) {

                    if (strlen($row->invoice_number) < $invoiceSettings->invoice_digit) {
                        for ($i = 0; $i < $invoiceSettings->invoice_digit - strlen($row->invoice_number); $i++) {
                            $zero = '0' . $zero;
                        }
                    }

                    $zero = $invoiceSettings->invoice_prefix . '#' . $zero . $row->invoice_number;
                    return '<a style="text-decoration: underline" href="' . route('client.invoices.show', $row->invoiceid) . '">' . $zero . '</a>';;
                }
                return "--";
            })
            ->editColumn(
                'issue_date',
                function ($row) {
                    return $row->issue_date->timezone($this->global->timezone)->format($this->global->date_format);
                }
            )
            ->editColumn('currency_symbol', function ($row) {
                return $row->currency_symbol . ' (' . $row->currency_code . ')';
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'open') {
                    return '<label class="label label-success">' . strtoupper($row->status) . '</label>';
                }
                else {
                    return '<label class="label label-danger">' . strtoupper($row->status) . '</label>';
                }
            })
            ->rawColumns(['action', 'status', 'cn_number', 'invoice_number'])
            ->removeColumn('currency_code')
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
            return download_local_s3($this->creditNote, 'credit-note-files/' . $this->creditNote->file);

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
            foreach (json_decode($item->taxes) as $tax){
                $this->tax = CreditNoteItem::taxbyid($tax)->first();
                if ($this->tax){
                    if (!isset($taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'])) {
                        $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = ($this->tax->rate_percent / 100) * $item->amount;
                    } else {
                        $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] = $taxList[$this->tax->tax_name . ': ' . $this->tax->rate_percent . '%'] + (($this->tax->rate_percent / 100) * $item->amount);
                    }
                }
            }
        }

        $this->taxes = $taxList;
        $this->settings = $this->company;
        $this->creditNoteSetting = InvoiceSetting::first();

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
            foreach (json_decode($item->taxes) as $tax){
                $this->tax = CreditNoteItem::taxbyid($tax)->first();
                if ($this->tax){
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
        $this->creditNoteSetting = InvoiceSetting::first();

        return view('client.credit-note.show', $this->data);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
       //
    }

}

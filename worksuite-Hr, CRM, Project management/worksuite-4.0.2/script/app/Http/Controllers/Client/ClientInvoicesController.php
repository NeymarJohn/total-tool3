<?php

namespace App\Http\Controllers\Client;

use App\ClientPayment;
use App\CreditNotes;
use App\Helper\Reply;
use App\Invoice;
use App\InvoiceItems;
use App\InvoiceSetting;
use App\OfflinePaymentMethod;
use App\PaymentGatewayCredentials;
use App\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Stripe\Stripe;
use Yajra\DataTables\Facades\DataTables;

class ClientInvoicesController extends ClientBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.invoices';
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
        return view('client.invoices.index', $this->data);
    }

    public function create()
    {
        $invoices = Invoice::leftJoin('projects', 'projects.id', '=', 'invoices.project_id')
            ->join('currencies', 'currencies.id', '=', 'invoices.currency_id')
            ->select('invoices.id', 'projects.project_name', 'invoices.invoice_number', 'currencies.currency_symbol', 'currencies.currency_code', 'invoices.total', 'invoices.issue_date', 'invoices.status')
            ->where(function ($query) {
                $query->where('projects.client_id', $this->user->id)
                    ->orWhere('invoices.client_id', $this->user->id);
            })
            ->where('invoices.credit_note', 0)
            ->where('invoices.status', '<>', 'draft')
            ->where('invoices.send_status', 1)

            ->where('invoices.status', '!=', 'canceled');

        return DataTables::of($invoices)
            ->addColumn('action', function ($row) {
                return '<a href="' . route('client.invoices.download', $row->id) . '" data-toggle="tooltip" data-original-title="Download" class="btn  btn-sm btn-outline btn-info"><i class="fa fa-download"></i> ' . __('app.download') . '</a>';
            })
            ->editColumn('project_name', function ($row) {
                return $row->project_name != '' ? $row->project_name : '--';
            })
            ->editColumn('invoice_number', function ($row) {
                return '<a style="text-decoration: underline" href="' . route('client.invoices.show', $row->id) . '">' . $row->invoice_number . '</a>';
            })
            ->editColumn('currency_symbol', function ($row) {
                return $row->currency_symbol . ' (' . $row->currency_code . ')';
            })
            ->editColumn('issue_date', function ($row) {
                return $row->issue_date->format($this->global->date_format);
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'unpaid') {
                    return '<label class="label label-danger">' . strtoupper($row->status) . '</label>';
                } else {
                    return '<label class="label label-success">' . strtoupper($row->status) . '</label>';
                }
            })
            ->rawColumns(['action', 'status', 'invoice_number'])
            ->removeColumn('currency_code')
            ->make(true);
    }

    public function download($id)
    {
        $this->invoice = Invoice::select('invoices.*')->leftJoin('projects', 'projects.id', '=', 'invoices.project_id')
            ->where(function ($query) {
                $query->where('projects.client_id', $this->user->id)
                    ->orWhere('invoices.client_id', $this->user->id);
            })->where('invoices.id', $id)->where('credit_note', 0)->findOrFail($id);


        $this->paidAmount = $this->invoice->getPaidAmount();
        $this->creditNote = 0;
        // if ($this->invoice->credit_note) {
        //     $this->creditNote = CreditNotes::where('invoice_id', $id)
        //         ->select('cn_number')
        //         ->first();
        // }

        // Download file uploaded
        if ($this->invoice->file != null) {
            return response()->download(storage_path('app/public/invoice-files') . '/' . $this->invoice->file);
        }

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

        $items = InvoiceItems::whereNotNull('taxes')
            ->where('invoice_id', $this->invoice->id)
            ->get();
        foreach ($items as $item) {
            foreach (json_decode($item->taxes) as $tax) {
                $this->tax = InvoiceItems::taxbyid($tax)->first();
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

        $this->invoiceSetting = invoice_setting();
        //        return view('invoices.'.$this->invoiceSetting->template, $this->data);

        $pdf = app('dompdf.wrapper');

        $pdf->getDomPDF()->set_option("enable_php", true);
        App::setLocale($this->invoiceSetting->locale);
        Carbon::setLocale($this->invoiceSetting->locale);
        $pdf->loadView('invoices.' . $this->invoiceSetting->template, $this->data);

        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(530, 820, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));

        $filename = $this->invoice->invoice_number;
        //       return $pdf->stream();
        return $pdf->download($filename . '.pdf');
    }

    public function show($id)
    {
        $this->invoice = Invoice::select('invoices.*')->leftJoin('projects', 'projects.id', '=', 'invoices.project_id')
               ->where(function ($query) {
                   $query->where('projects.client_id', $this->user->id)
                       ->orWhere('invoices.client_id', $this->user->id);
               })->where('invoices.id', $id)->where('credit_note', 0)->findOrFail($id);

        $this->paidAmount = $this->invoice->getPaidAmount();

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



        $items = InvoiceItems::whereNotNull('taxes')
            ->where('invoice_id', $this->invoice->id)
            ->get();
        foreach ($items as $item) {
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

        $this->settings = $this->global;
        $this->credentials = PaymentGatewayCredentials::first();
        $this->methods = OfflinePaymentMethod::activeMethod();
        $this->invoiceSetting = invoice_setting();

        return view('client.invoices.show', $this->data);
    }

    public function stripeModal(Request $request){
        $id = $request->invoice_id;
        $this->invoice = Invoice::with(['client', 'project', 'project.client'])->findOrFail($id);

        $this->settings = $this->global;
        $this->credentials = PaymentGatewayCredentials::first();

        $client = null;
        if(!is_null($this->invoice->client_id))
        {
            $client = $this->invoice->client;
        }
        else if(!is_null($this->invoice->project_id) && !is_null($this->invoice->project->client_id))
        {
            $client = $this->invoice->project->client;
        }

        if($this->credentials->stripe_secret && !is_null($client))
        {
            Stripe::setApiKey($this->credentials->stripe_secret);

            $total = $this->invoice->total;
            $totalAmount = $total;

            $customer = \Stripe\Customer::create([
                'email' => $client->email,
                'name' => $request->clientName,
                'address' => [
                    'line1' => $request->clientName,
                    'city' => $request->city,
                    'state' => $request->state,
                    'country' => $request->country,
                ],
            ]);

            $intent = \Stripe\PaymentIntent::create([
                'amount' => $totalAmount*100,
                'currency' => $this->invoice->currency->currency_code,
                'customer' => $customer->id,
                'setup_future_usage' => 'off_session',
                'payment_method_types' => ['card'],
                'description' => $this->invoice->invoice_number. ' Payment',
                'metadata' => ['integration_check' => 'accept_a_payment', 'invoice_id' => $id]
            ]);

            $this->intent = $intent;
        }
        $customerDetail = [
            'email' => $client->email,
            'name' => $request->clientName,
            'line1' => $request->clientName,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
        ];

        $this->customerDetail = $customerDetail;

        $view = view('client.invoices.stripe-payment', $this->data)->render();

        return Reply::dataOnly(['view' => $view]);
    }


    public function store(Request $request)
    {
        $invoiceId = $request->invoiceId;
        $invoice = Invoice::findOrFail($invoiceId);

        $clientPayment = new ClientPayment();
        $clientPayment->currency_id = $invoice->currency_id;
        $clientPayment->invoice_id = $invoice->id;
        $clientPayment->project_id = $invoice->project_id;
        $clientPayment->amount = $invoice->total;
        $clientPayment->offline_method_id = $request->offlineMethod;
        $clientPayment->gateway = 'Offline';
        $clientPayment->status = 'complete';
        $clientPayment->paid_on = Carbon::now();

        if ($request->hasFile('bill')) {
            $clientPayment->bill = $request->bill->hashName();
            $request->bill->store('payment-receipt');
        }

        $clientPayment->save();

        $invoice->status = 'paid';
        $invoice->save();

        return Reply::redirect(route('client.invoices.show', $invoiceId), __('messages.paymentSuccess'));
    }
}

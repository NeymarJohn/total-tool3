<?php

namespace App\Http\Controllers\Client;

use App\Helper\Reply;
use App\Invoice;
use App\InvoiceItems;
use App\InvoiceSetting;
use App\OfflinePaymentMethod;
use App\PaymentGatewayCredentials;
use App\RecurringInvoice;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClientInvoiceRecurringController extends ClientBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.recurringInvoices';
        $this->pageIcon = 'ti-receipt';

        $this->middleware(function ($request, $next) {
            if (!in_array('invoices', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('client.invoice-recurring.index', $this->data);
    }

    /**
     * @return mixed
     */
    public function create()
    {
        $invoices = RecurringInvoice::leftJoin('projects', 'projects.id', '=', 'invoice_recurring.project_id')
            ->join('currencies', 'currencies.id', '=', 'invoice_recurring.currency_id')
            ->select('invoice_recurring.id', 'projects.project_name',  'currencies.currency_symbol', 'currencies.currency_code', 'invoice_recurring.total', 'invoice_recurring.issue_date', 'invoice_recurring.status', 'invoice_recurring.client_can_stop')
            ->where(function ($query) {
                $query->where('projects.client_id', $this->user->id)
                    ->orWhere('invoice_recurring.client_id', $this->user->id);
            });
//            ->where('invoice_recurring.status', 'active');

        return DataTables::of($invoices)
            ->addColumn('action', function ($row) {
                return '<a href="' . route('client.invoice-recurring.show', $row->id) . '" data-toggle="tooltip" data-original-title="View" class="btn  btn-sm btn-outline btn-info"><i class="fa fa-search"></i> '.__('app.view').'</a>';
            })
            ->editColumn('project_name', function ($row) {
                return $row->project_name != '' ? $row->project_name : '--';
            })
            ->editColumn('currency_symbol', function ($row) {
                return $row->currency_symbol . ' (' . $row->currency_code . ')';
            })
            ->editColumn('issue_date', function ($row) {
                return $row->issue_date->format($this->global->date_format);
            })
            ->editColumn('status', function ($row) {
                if($row->client_can_stop)
                {
                    $status = '<div class="btn-group dropdown">';
                    if ($row->status == 'active') {
                        $status .= '<button aria-expanded="true" data-toggle="dropdown" class="btn dropdown-toggle waves-effect waves-light btn-xs btn-success" type="button">' . ucfirst($row->status) . ' <span class="caret"></span></button>';
                    } else {
                        $status .= '<button aria-expanded="true" data-toggle="dropdown" class="btn dropdown-toggle waves-effect waves-light btn-xs btn-danger" type="button">' . ucfirst($row->status) . ' <span class="caret"></span></button>';
                    }
                    $status .= '<ul role="menu" class="dropdown-menu pull-right">';
                    $status .= '<li><a href="javascript:;" data-invoice-id="' . $row->id . '" class="change-status" data-status="active">' . __('app.active') . '</a></li>';
                    $status .= '<li><a href="javascript:;" data-invoice-id="' . $row->id . '" class="change-status" data-status="inactive">' . __('app.inactive') . '</a></li>';
                    $status .= '</ul>';
                    $status .= '</div>';
                    return $status;
                }

                if ($row->status == 'inactive') {
                    return '<label class="label label-danger">' . strtoupper($row->status) . '</label>';
                } else {
                    return '<label class="label label-success">' . strtoupper($row->status) . '</label>';
                }
            })
            ->rawColumns(['action', 'status'])
            ->removeColumn('currency_code')
            ->removeColumn('client_can_stop')
            ->make(true);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $this->invoice = RecurringInvoice::with('recurrings')
            ->whereHas('project', function ($q) {
                $q->where('client_id', $this->user->id);
            }, '>=', 0)
            ->findOrFail($id);

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
            foreach (json_decode($item->taxes) as $tax){
                $this->tax = InvoiceItems::taxbyid($tax)->first();
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
        $this->credentials = PaymentGatewayCredentials::first();
        $this->methods = OfflinePaymentMethod::activeMethod();

        $this->invoiceSetting = InvoiceSetting::first();

        return view('client.invoice-recurring.show', $this->data);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function invoice($id)
    {
        $this->invoice = RecurringInvoice::with('recurrings')
            ->whereHas('project', function ($q) {
                $q->where('client_id', $this->user->id);
            }, '>=', 0)
            ->findOrFail($id);
        return view('client.invoice-recurring.recurring-invoices', $this->data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function recurringInvoice($id)
    {
        $invoices = Invoice::leftJoin('projects', 'projects.id', '=', 'invoices.project_id')
            ->join('currencies', 'currencies.id', '=', 'invoices.currency_id')
            ->select('invoices.id', 'projects.project_name', 'invoices.invoice_number', 'currencies.currency_symbol', 'currencies.currency_code', 'invoices.total', 'invoices.issue_date', 'invoices.status')
            ->where(function ($query) {
                $query->where('projects.client_id', $this->user->id)
                    ->orWhere('invoices.client_id', $this->user->id);
            })
            ->where('invoices.status', '<>', 'draft')
            ->where('invoices.send_status', 1)
            ->where('invoices.status', '!=', 'canceled')
            ->where('invoices.invoice_recurring_id', $id);

        return DataTables::of($invoices)
            ->addColumn('action', function ($row) {
                return '<a href="' . route('client.invoices.download', $row->id) . '" data-toggle="tooltip" data-original-title="Download" class="btn  btn-sm btn-outline btn-info"><i class="fa fa-download"></i> '.__('app.download').'</a>';
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
                } else if($row->status == 'review') {
                    return '<label class="label label-warning">' . strtoupper($row->status) . '</label>';
                } else {
                    return '<label class="label label-success">' . strtoupper($row->status) . '</label>';
                }
            })
            ->rawColumns(['action', 'status', 'invoice_number'])
            ->removeColumn('currency_code')
            ->make(true);
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

        if($request->status == 'active'){
            return Reply::success(__('messages.recurringStarted'));
        }
        return Reply::success(__('messages.recurringStopped'));
    }
}

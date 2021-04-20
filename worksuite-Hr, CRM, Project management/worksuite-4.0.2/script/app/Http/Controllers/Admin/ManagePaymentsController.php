<?php

namespace App\Http\Controllers\Admin;

use App\Currency;
use App\DataTables\Admin\PaymentsDataTable;
use App\Helper\Reply;
use App\Http\Requests\Payments\ImportPayment;
use App\Http\Requests\Payments\StorePayment;
use App\Http\Requests\Payments\UpdatePayments;
use App\Imports\PaymentImport;
use App\Invoice;
use App\Payment;
use App\Project;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use App\Helper\Files;

class ManagePaymentsController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.payments';
        $this->pageIcon = 'fa fa-money';
        $this->middleware(function ($request, $next) {
            if (!in_array('payments', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(PaymentsDataTable $dataTable)
    {
        if (!request()->ajax()) {
            $this->projects = Project::allProjects();
            $this->clients = User::allClients();
        }
        return $dataTable->render('admin.payments.index', $this->data);
    }

    public function create()
    {
        $this->projects = Project::allProjects();
        $this->currencies = Currency::all();
        if (request()->get('project')) {
            $this->projectId = request()->get('project');
        }
        return view('admin.payments.create', $this->data);
    }

    public function store(StorePayment $request)
    {
        $payment = new Payment();
        if(!is_null($request->currency_id)){
            $payment->currency_id = $request->currency_id;
        }
        else{
            $payment->currency_id = $this->global->currency_id;
        }
        if ($request->project_id != '') {
            $payment->project_id = $request->project_id;
        } else if ($request->has('invoice_id')) {
            $invoice = Invoice::findOrFail($request->invoice_id);
            $payment->project_id = $invoice->project_id;
            $payment->invoice_id = $invoice->id;
            $payment->currency_id = $invoice->currency->id;
            $paidAmount = $invoice->amountPaid();
        }

        $payment->amount = round($request->amount, 2);
        $payment->gateway = $request->gateway;
        $payment->transaction_id = $request->transaction_id;
        $payment->paid_on =  Carbon::createFromFormat('d/m/Y H:i', $request->paid_on)->format('Y-m-d H:i:s');

        $payment->remarks = $request->remarks;

        if ($request->hasFile('bill')) {
            $payment->bill = $request->bill->hashName();
            $request->bill->store('payment-receipt');
        }
        $payment->save();

        if ($request->has('invoice_id')) {
            
            if (($paidAmount + $request->amount) >= $invoice->total) {
                $invoice->status = 'paid';
            } else {
                $invoice->status = 'partial';
            }
            $invoice->save();
        }

        if ($request->has('project_id_direct')) {
            return Reply::redirect(route('admin.projects.show', $payment->project_id), __('messages.paymentSuccess'));
        }

        return Reply::redirect(route('admin.payments.index'), __('messages.paymentSuccess'));
    }

    public function destroy($id)
    {
        $payment = Payment::with('invoice')->find($id);

        // change invoice status if exists
        if ($payment->invoice) {
            $due = $payment->invoice->amountDue() + $payment->amount;
            if ($due <= 0) {
                $payment->invoice->status = 'paid';
            } else if ($due >= $payment->invoice->total) {
                $payment->invoice->status = 'unpaid';
            } else {
                $payment->invoice->status = 'partial';
            }
            $payment->invoice->save();
        }

        $payment->delete();

        return Reply::success(__('messages.paymentDeleted'));
    }

    public function edit($id)
    {
        $this->projects = Project::allProjects();
        $this->currencies = Currency::all();
        $this->payment = Payment::findOrFail($id);
        return view('admin.payments.edit', $this->data);
    }

    public function update(UpdatePayments $request, $id)
    {

        $payment = Payment::findOrFail($id);
        if ($request->project_id != '') {
            $payment->project_id = $request->project_id;
        }
        $payment->currency_id = $request->currency_id;
        $payment->amount = round($request->amount, 2);
        $payment->gateway = $request->gateway;
        $payment->transaction_id = $request->transaction_id;

        if ($request->paid_on != '') {
            $payment->paid_on = Carbon::createFromFormat('d/m/Y H:i', $request->paid_on)->format('Y-m-d H:i:s');
        } else {
            $payment->paid_on = null;
        }

        $payment->status = $request->status;
        $payment->remarks = $request->remarks;

        if ($request->hasFile('bill')) {
            Files::deleteFile($payment->bill, 'payment-receipt');
            $payment->bill = $request->bill->hashName();
            $request->bill->store('payment-receipt');
        }

        $payment->save();

        // change invoice status if exists
        if ($payment->invoice) {
            if ($payment->invoice->amountDue() <= 0) {
                $payment->invoice->status = 'paid';
            } else if ($payment->invoice->amountDue() >= $payment->invoice->total) {
                $payment->invoice->status = 'unpaid';
            } else {
                $payment->invoice->status = 'partial';
            }
            $payment->invoice->save();
        }

        return Reply::redirect(route('admin.payments.index'), __('messages.paymentSuccess'));
    }

    public function payInvoice($invoiceId)
    {
        $this->invoice = Invoice::findOrFail($invoiceId);
        $this->paidAmount = $this->invoice->amountPaid();


        if ($this->invoice->status == 'paid') {
            return "Invoice already paid";
        }

        return view('admin.payments.pay-invoice', $this->data);
    }

    public function importExcel(ImportPayment $request)
    {
        if ($request->hasFile('import_file')) {
            Excel::import(new PaymentImport($request, $this->global), $request->file('import_file'));
        }

        return Reply::redirect(route('admin.payments.index'), __('messages.importSuccess'));
    }

    public function downloadSample()
    {
        return response()->download(public_path() . '/payment-sample.csv');
    }

    public function show($id)
    {
        $this->payment = Payment::with('invoice', 'project', 'currency')->find($id);
        return view('admin.payments.show', $this->data);
    }

}

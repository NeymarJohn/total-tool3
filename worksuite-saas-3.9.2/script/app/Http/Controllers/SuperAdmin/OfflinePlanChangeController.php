<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Company;
use App\GlobalCurrency;
use App\GlobalSetting;
use App\Helper\Reply;
use App\Http\Requests\SuperAdmin\Settings\UpdateGlobalSettings;
use App\Notifications\OfflinePackageChangeConfirmation;
use App\OfflineInvoice;
use App\OfflinePlanChange;
use App\Package;
use App\Traits\GlobalCurrencyExchange;
use App\LanguageSetting;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OfflinePlanChangeController extends SuperAdminBaseController
{
    /**
     * SuperAdminInvoiceController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Offline Plan Change';
        $this->pageIcon = 'icon-settings';
    }

    /**
     * Display edit form of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->global = GlobalSetting::first();
        $this->totalRequest = OfflinePlanChange::count();
        return view('super-admin.offline-plan-change.index', $this->data);
    }
    public function data(Request $request)
    {
        $users = OfflinePlanChange::with('company', 'package', 'offline_method');
        return DataTables::of($users)
            ->addColumn('action', function($row) {
                $string = '';
                if($row->status == 'pending') {
                    $string .=  '<a href="javascript:;" onclick="accept('.$row->id.')" class="btn btn-info btn-circle"
                      data-toggle="tooltip" data-original-title="Verify"><i class="fa fa-check" aria-hidden="true"></i></a>
                      <a href="javascript:;" onclick="reject('.$row->id.')" class="btn btn-danger btn-circle"
                      data-toggle="tooltip" data-original-title="Reject"><i class="fa fa-remove" aria-hidden="true"></i></a>';

                }
                $string .= '<a href="'.$row->file.'" target="_blank" class="btn btn-default btn-circle m-l-5"
                      data-toggle="tooltip" data-original-title="View File"><i class="fa fa-eye" aria-hidden="true"></i></a>';

                return $string;
            })
            ->editColumn(
                'status',
                function ($row) {
                    $status = ['pending' => 'warning', 'verified' => 'success', 'rejected' => 'danger'];
                    return '<label class="label label-'.$status[$row->status].'">'.ucwords($row->status).'</label>';

                }
            )
            ->rawColumns(['name', 'action', 'status', 'file_name'])
            ->make(true);
    }

    public function verify(Request $request)
    {
        $offlinePlanChnage = OfflinePlanChange::findOrFail($request->id);
        $invoice = OfflineInvoice::findOrFail($offlinePlanChnage->invoice_id);
        $company = Company::find($offlinePlanChnage->company_id);

        // Change company package
        $company->package_id = $offlinePlanChnage->package_id;
        $company->package_type = $offlinePlanChnage->package_type;
        $company->save();

        // set status of invoice paid
        $invoice->status = 'paid';
        $invoice->save();

        // set status of request verified
        $offlinePlanChnage->status = 'verified';
        $offlinePlanChnage->save();

        return Reply::success('Request successfully verified');
    }

    public function reject(Request $request)
    {
        $offlinePlanChnage = OfflinePlanChange::findOrFail($request->id);
        $company = Company::find($offlinePlanChnage->company_id);
        // set status of request verified
        $offlinePlanChnage->status = 'rejected';
        $offlinePlanChnage->save();

        return Reply::success('Request successfully rejected');
    }
}

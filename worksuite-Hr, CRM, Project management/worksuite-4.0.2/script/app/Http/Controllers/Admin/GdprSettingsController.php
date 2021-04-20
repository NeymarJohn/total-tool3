<?php

namespace App\Http\Controllers\Admin;

use App\GdprSetting;
use App\Helper\Reply;
use App\Http\Requests\Gdpr\CreateRequest;
use App\PurposeConsent;
use App\RemovalRequest;
use App\RemovalRequestLead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GdprSettingsController extends AdminBaseController
{

    public function __construct()
    {
        parent:: __construct();
        $this->pageTitle = 'app.menu.gdpr';
        $this->pageIcon = 'icon-settings';
        $this->gdprSetting = GdprSetting::first();
    }


    public function index()
    {
        return view('admin.gdpr.index', $this->data);
    }

    public function rightToErasure()
    {
        return view('admin.gdpr.right_to_erasure', $this->data);
    }

    public function rightToDataPortability()
    {

        return view('admin.gdpr.right_to_data_portability', $this->data);
    }

    public function rightToInformed()
    {

        return view('admin.gdpr.right_to_informed', $this->data);
    }

    public function rightOfAccess()
    {

        return view('admin.gdpr.right_of_access', $this->data);
    }

    public function consent()
    {

        return view('admin.gdpr.consent', $this->data);
    }

    public function addConsent()
    {
        return view('admin.gdpr.add_consent', $this->data);
    }

    public function editConsent($id)
    {
        $this->consent = PurposeConsent::findOrFail($id);
        return view('admin.gdpr.edit_consent', $this->data);
    }

    public function store(Request $request)
    {
        $this->gdprSetting->update($request->all());
        return Reply::success(__('messages.gdprUpdated'));
    }

    public function storeConsent(CreateRequest $request)
    {
        $consent = new PurposeConsent();
        $consent->create($request->all());

        return Reply::success(__('messages.gdprUpdated'));
    }

    public function updateConsent(CreateRequest $request, $id)
    {
        $consent = PurposeConsent::findOrFail($id);
        $consent->update($request->all());

        return Reply::success(__('messages.gdprUpdated'));
    }

    public function data(Request $request)
    {
        $purpose = PurposeConsent::select('id', 'name', 'description', 'created_at')->get();


        return DataTables::of($purpose)
            ->addColumn('action', function ($row) {
                $action = '';

                $action .= '<a href="javascript:;"  class="btn btn-info btn-circle sa-params-edit"
                      data-toggle="tooltip" data-user-id="' . $row->id . '" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';


                $action .= ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-user-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                return $action;
            })
            ->editColumn(
                'created_at',
                function ($row) {
                    return Carbon::parse($row->created_at)->format($this->global->date_format);
                }
            )
            ->make(true);
    }

    public function removalData(Request $request)
    {
        $purpose = RemovalRequest::select('id', 'name', 'description', 'created_at', 'status')->get();


        return DataTables::of($purpose)
            ->editColumn('status', function ($row) {

                if ($row->status == 'pending') {
                    $status = '<label class="label label-info">' . __('app.pending') . '</label>';
                } else if ($row->status == 'approved') {
                    $status = '<label class="label label-success">' . __('app.approved') . '</label>';
                } else if ($row->status == 'rejected') {
                    $status = '<label class="label label-danger">' . __('app.rejected') . '</label>';
                }
                return $status;
            })
            ->addColumn('action', function ($row) {
                $action = '';

                if($row->status =='approved'){
                    return '';
                }
                $action .= '<button  class="btn btn-success btn-circle sa-params"
                      data-toggle="tooltip" data-user-id="' . $row->id . '" data-type="approved"  data-original-title="Approve"><i class="fa fa-check" aria-hidden="true"></i></button>';


                $action .= ' <button class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-user-id="' . $row->id . '" data-type="rejected" data-original-title="Reject"><i class="fa fa-times" aria-hidden="true"></i></button>';
                return $action;
            })
            ->editColumn(
                'created_at',
                function ($row) {
                    return Carbon::parse($row->created_at)->format($this->global->date_format);
                }
            )
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function purposeDelete($id)
    {
        PurposeConsent::destroy($id);
        return Reply::success("Deleted successfully");
    }

    public function approveReject($id, $type)
    {
        $removal = RemovalRequest::findorFail($id);
        $removal->status = $type;
        $removal->save();
        try {
            if ($type == 'approved' && $removal->user) {
                $removal->user->delete();
            }

        } catch (\Exception $e) {

        }


        return Reply::success("Approved successfully");
    }

    public function removalLeadData(Request $request)
    {
        $purpose = RemovalRequestLead::select('id', 'name', 'description', 'created_at', 'status')->get();


        return DataTables::of($purpose)
            ->editColumn('status', function ($row) {

                if ($row->status == 'pending') {
                    $status = '<label class="label label-info">' . __('app.pending') . '</label>';
                } else if ($row->status == 'approved') {
                    $status = '<label class="label label-success">' . __('app.approved') . '</label>';
                } else if ($row->status == 'rejected') {
                    $status = '<label class="label label-danger">' . __('app.rejected') . '</label>';
                }
                return $status;
            })
            ->addColumn('action', function ($row) {
                $action = '';

                if($row->status =='approved'){
                    return '';
                }
                $action .= '<button  class="btn btn-success btn-circle sa-params1"
                      data-toggle="tooltip" data-lead-id="' . $row->id . '" data-type="approved"  data-original-title="Approve"><i class="fa fa-check" aria-hidden="true"></i></button>';


                $action .= ' <button class="btn btn-danger btn-circle sa-params1"
                      data-toggle="tooltip" data-lead-id="' . $row->id . '" data-type="rejected" data-original-title="Reject"><i class="fa fa-times" aria-hidden="true"></i></button>';
                return $action;
            })
            ->editColumn(
                'created_at',
                function ($row) {
                    return Carbon::parse($row->created_at)->format($this->global->date_format);
                }
            )
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function approveRejectLead($id, $type)
    {
        $removal = RemovalRequestLead::findorFail($id);
        $removal->status = $type;
        $removal->save();

        try {
            if ($type == 'approved' && $removal->lead) {
                $removal->lead->delete();
            }

        } catch (\Exception $e) {

        }

        return Reply::success(ucfirst($type). " successfully");
    }
}

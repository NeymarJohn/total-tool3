<?php

namespace App\Http\Controllers\Admin;

use App\ClientContact;
use App\ContractType;
use App\Helper\Reply;
use App\Http\Requests\Admin\ContractType\StoreRequest;
use App\Http\Requests\Admin\ContractType\UpdateRequest;
use App\User;
use Yajra\DataTables\Facades\DataTables;

class AdminContractTypeController extends AdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageIcon = 'fa fa-file';
        $this->pageTitle = 'app.menu.contracts';
        $this->middleware(function ($request, $next) {
            if(!in_array('contracts',$this->user->modules)){
                abort(403);
            }
            return $next($request);
        });

    }

    public function index() {
        $this->pageIcon = '';
        $this->pageTitle = 'app.menu.contracts';
        return view('admin.contract-type.index', $this->data);
    }

    public function data() {
        $contractType = ContractType::all();

        return DataTables::of($contractType)
            ->addColumn('action', function($row){
                return '<a href="javascript:;" class="btn btn-info btn-circle edit-contact"
                      data-toggle="tooltip" onclick="editContractType('.$row->id.')"  data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                    <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-contract-id="'.$row->id.'" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
            })
            ->editColumn('name', function($row){
                return ucwords($row->name);
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function create() {

        return view('admin.contract-type.create-modal');
    }
    public function createContractType()
    {
        $this->contractType = ContractType::all();
        return view('admin.contracts.create-contract-type', $this->data);
    }

    public function storeContractType(StoreRequest $request)
    {
        $contractType = new ContractType();
        $contractType->name = $request->name;
        $contractType->save();
        $contractTypeData = ContractType::all();
        return Reply::successWithData(__('messages.contractTypeAdded'),['data' => $contractTypeData]);
    }
    public function store(StoreRequest $request) {
        $contract = new ContractType();
        $contract->name = $request->name;
        $contract->save();

        return Reply::success(__('messages.contractTypeAdded'));
    }

    public function edit($id) {
        $this->contract = ContractType::findOrFail($id);
        return view('admin.contract-type.edit', $this->data);
    }

    public function update(UpdateRequest $request, $id) {
        $contract = ContractType::findOrFail($id);
        $contract->name = $request->name;
        $contract->save();

        return Reply::success(__('messages.contractTypeUpdated'));
    }

    public function destroy($id) {
        ContractType::destroy($id);
        $contractTypeData = ContractType::all();
        return Reply::successWithData(__('messages.contractTypeDeleted'),['data' => $contractTypeData]);
    }

    public function download($id)
    {

    }
}

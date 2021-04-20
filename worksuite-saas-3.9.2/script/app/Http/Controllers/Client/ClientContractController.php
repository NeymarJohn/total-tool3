<?php

namespace App\Http\Controllers\Client;

use App\ClientContact;
use App\ClientDetails;
use App\Contract;
use App\ContractDiscussion;
use App\ContractSign;
use App\ContractType;
use App\Helper\Reply;
use App\Http\Controllers\Client\ClientBaseController;
use App\Http\Requests\Admin\Contract\UpdateDiscussionRequest;
use App\Http\Requests\ClientContracts\SignRequest;
use App\Http\Requests\ClientContracts\StoreDiscussionRequest;
use App\Http\Requests\ClientContacts\StoreContact;
use App\Notifications\ContractSigned;
use App\Scopes\CompanyScope;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Yajra\DataTables\Facades\DataTables;

class ClientContractController extends ClientBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageIcon = 'fa fa-file';
        $this->pageTitle = 'contracts';

        $this->middleware(function ($request, $next) {
            if(!in_array('contracts',$this->user->modules)){
                abort(403);
            }
            return $next($request);
        });

    }

    public function index() {
        $this->contractType = ContractType::all();
        return view('client.contracts.index', $this->data);
    }

    public function data(Request $request) {
        $contract = Contract::with('contract_type', 'signature')->where('client_id', $this->user->id)
        ->where('contracts.send_status', 1);

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $contract = $contract->where(DB::raw('DATE(contracts.`start_date`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $contract = $contract->where(DB::raw('DATE(contracts.`end_date`)'), '<=', $startDate);
        }

        if ($request->contractType != 'all' && !is_null($request->contractType)) {
            $contract = $contract->where('contracts.contract_type_id', '=', $request->contractType);
        }


        return DataTables::of($contract)
            ->addColumn('action', function($row) {
                return '<a href="'.route('client.contracts.show', md5($row->id)).'" target="_blank" class="btn btn-info btn-circle view-contact"
                      data-toggle="tooltip" data-contract-id="'.$row->id.'"  data-original-title="View"><i class="fa fa-eye" aria-hidden="true"></i></a>';
            })
            ->editColumn('start_date', function($row) {
                return $row->start_date->format($this->global->date_format);
            })
            ->editColumn('subject', function($row) {
                return '<a href="'.route('client.contracts.show', md5($row->id)).'">'.ucfirst($row->subject).'</a>';
            })
            ->editColumn('end_date', function($row) {
                if(!is_null($row->end_date)){
                    return $row->end_date->format($this->global->date_format);
                }
            })
            ->editColumn('amount', function($row) {
                return $this->global->currency->currency_symbol.''.$row->amount;
            })
            ->editColumn('signature', function($row) {
                if($row->signature) {
                    return 'signed';
                }
                return 'Not Signed';
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'subject'])
            ->make(true);
    }

    public function show($id) {
        $this->contract = Contract::withoutGlobalScope(CompanyScope::class)->whereRaw('md5(id) = ?', $id)
            ->with('client', 'contract_type', 'signature', 'discussion', 'discussion.user')
            ->first();

        return view('client.contracts.show', $this->data);
    }

    public function download($id)
    {
        $this->contract = Contract::withoutGlobalScope(CompanyScope::class)->findOrFail($id);
        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('client.contracts.contract-pdf', $this->data);

        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(530, 820, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        $filename = 'contract-' . $this->contract->id;

        return $pdf->download($filename . '.pdf');
    }

    public function addDiscussion(StoreDiscussionRequest $request, $id)
    {
        $contractDiscussion = new ContractDiscussion();
        $contractDiscussion->from = $this->user->id;
        $contractDiscussion->message = $request->message;
        $contractDiscussion->contract_id = $id;
        $contractDiscussion->save();



        return Reply::redirect(route('client.contracts.show', md5($id).'#discussion'), __('messages.addDiscussion'));
    }

    public function signModal($id)
    {
        $this->contract = Contract::withoutGlobalScope(CompanyScope::class)->find($id);
        return view('client.contracts.accept', $this->data);
    }

    public function sign(SignRequest $request, $id)
    {
        $this->contract =Contract::withoutGlobalScope(CompanyScope::class)->whereRaw('md5(id) = ?', $id)->firstOrFail();

        if(!$this->contract) {
            return Reply::error('you are not authorized to access this.');
        }

        $sign = new ContractSign();
        $sign->full_name = $request->first_name. ' '. $request->last_name;
        $sign->contract_id = $this->contract->id;
        $sign->email = $request->email;
        $sign->company_id = $this->contract->company_id;

        $image = $request->signature;  // your base64 encoded
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = str_random(32).'.'.'jpg';
        if (!\File::exists(public_path('user-uploads/' . 'contract/sign'))) {
            $result = \File::makeDirectory(public_path('user-uploads/contract/sign'), 0775, true);
        }

        \File::put(public_path(). '/user-uploads/contract/sign/' . $imageName, base64_decode($image));

        $sign->signature = $imageName;
        $sign->save();

        $allAdmins =  User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', 'admin')
            ->where('users.company_id', $this->contract->company_id)
            ->get();

        Notification::send($allAdmins, new ContractSigned($this->contract, $sign));


        return Reply::redirect(route('client.contracts.show', md5($this->contract->id)));

    }


    public function editDiscussion($id)
    {
        $this->discussion = ContractDiscussion::find($id);
        return view('client.contracts.edit-discussion', $this->data);
    }

    public function updateDiscussion(UpdateDiscussionRequest $request, $id)
    {
        $this->discussion = ContractDiscussion::find($id);
        $this->discussion->message = $request->messages;
        $this->discussion->save();

        return Reply::success(__('modules.contracts.discussionUpdated'));
    }

    public function removeDiscussion($id)
    {
        ContractDiscussion::destroy($id);

        return Reply::success(__('modules.contracts.discussionDeleted'));
    }
}

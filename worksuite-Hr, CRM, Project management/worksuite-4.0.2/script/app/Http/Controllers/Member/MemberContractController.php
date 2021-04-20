<?php

namespace App\Http\Controllers\Member;

use App\Contract;
use App\ContractDiscussion;
use App\ContractSign;
use App\ContractType;
use App\DataTables\Member\ContractsDataTable;
use App\Helper\Reply;
use App\Http\Requests\Admin\Contract\SignRequest;
use App\Http\Requests\Admin\Contract\StoreDiscussionRequest;
use App\Http\Requests\Admin\Contract\StoreRequest;
use App\Http\Requests\Admin\Contract\UpdateDiscussionRequest;
use App\Http\Requests\Admin\Contract\UpdateRequest;
use App\User;
use App\Helper\Files;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class MemberContractController extends MemberBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'fa fa-file';
        $this->pageTitle = 'app.menu.contracts';
        $this->middleware(function ($request, $next) {
            if (!in_array('contracts', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(ContractsDataTable $dataTable)
    {
        if (!$this->user->can('view_contract')) {
            abort(403);
        }
        $this->clients = User::allClients();
        $this->contractType = ContractType::all();
        $this->contractCounts = Contract::count();
        $this->expiredCounts = Contract::where(DB::raw('DATE(`end_date`)'), '<', Carbon::now()->format('Y-m-d'))->count();
        $this->aboutToExpireCounts = Contract::where(DB::raw('DATE(`end_date`)'), '>', Carbon::now()->format('Y-m-d'))
            ->where(DB::raw('DATE(`end_date`)'), '<', Carbon::now()->timezone($this->global->timezone)->addDays(7)->format('Y-m-d'))
            ->count();
        return $dataTable->render('member.contracts.index', $this->data);
    }

    public function create()
    {
        if (!$this->user->can('add_contract')) {
            abort(403);
        }
        $this->clients = User::allClients();
        $this->contractType = ContractType::all();
        return view('member.contracts.create', $this->data);
    }

    public function store(StoreRequest $request)
    {
        $contract = new Contract();
        $contract = $this->storeUpdate($request, $contract);
        return Reply::redirect(route('member.contracts.edit', $contract->id), __('messages.contractAdded'));
    }

    public function edit($id)
    {
        if (!$this->user->can('edit_contract')) {
            abort(403);
        }
        $this->clients = User::allClients();
        $this->contractType = ContractType::all();
        $this->contract = Contract::with('signature', 'renew_history', 'renew_history.renewedBy')->find($id);
        return view('member.contracts.edit', $this->data);
    }

    public function update(UpdateRequest $request, $id)
    {
        $contract = Contract::findOrFail($id);
        $this->storeUpdate($request, $contract);

        return Reply::redirect(route('member.contracts.index'), __('messages.contractUpdated'));
    }

    public function show($id)
    {
        if (!$this->user->can('view_contract')) {
            abort(403);
        }
        $this->contract = Contract::whereRaw('md5(id) = ?', $id)
            ->with('client', 'contract_type', 'signature', 'discussion', 'discussion.user')
            ->firstOrFail();

        return view('member.contracts.show', $this->data);
    }

    private function storeUpdate($request, $contract)
    {
        $contract->client_id = $request->client;
        $contract->subject = $request->subject;
        $contract->amount = $request->amount;
        $contract->original_amount = $request->amount;
        $contract->contract_name = $request->contract_name;
        $contract->alternate_address = $request->alternate_address;
        $contract->cell = $request->cell;
        $contract->office = $request->office;
        $contract->city = $request->city;
        $contract->state = $request->state;
        $contract->country = $request->country;
        $contract->postal_code = $request->postal_code;
        $contract->contract_type_id = $request->contract_type;
        $contract->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
        $contract->original_start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
        $contract->end_date = $request->end_date== null ? $request->end_date : Carbon::createFromFormat($this->global->date_format, $request->end_date)->format('Y-m-d');
        $contract->original_end_date = $request->end_date== null  ? $request->end_date : Carbon::createFromFormat($this->global->date_format, $request->end_date)->format('Y-m-d');
        $contract->description = $request->description;
        if ($request->hasFile('company_logo')) {
            Files::deleteFile($contract->company_logo, 'contract-logo');
            $contract->company_logo = Files::upload($request->company_logo, 'contract-logo', 300);
        }
        if ($request->contract_detail) {
            $contract->contract_detail = $request->contract_detail;
        }
        $contract->save();

        return $contract;
    }

    public function destroy($id)
    {
        if (!$this->user->can('delete_contract')) {
            abort(403);
        }
        Contract::destroy($id);

        return Reply::success(__('messages.contactDeleted'));
    }

    public function download($id)
    {
        $this->contract = Contract::findOrFail($id);
        $pdf = app('dompdf.wrapper');

        $pdf->getDomPDF()->set_option("enable_php", true);
        App::setLocale($this->invoiceSetting->locale);
        Carbon::setLocale($this->invoiceSetting->locale);
        $pdf->loadView('member.contracts.contract-pdf', $this->data);

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

        return Reply::redirect(route('member.contracts.show', md5($id) . '#discussion'), __('messages.addDiscussion'));
    }

    public function signModal($id)
    {
        $this->contract = Contract::find($id);
        return view('member.contracts.accept', $this->data);
    }

    public function sign(SignRequest $request, $id)
    {
        $this->contract = Contract::whereRaw('md5(id) = ?', $id)->firstOrFail();

        if (!$this->contract) {
            return Reply::error('you are not authorized to access this.');
        }

        $sign = new ContractSign();
        $sign->full_name = $request->first_name . ' ' . $request->last_name;
        $sign->contract_id = $this->contract->id;
        $sign->email = $request->email;

        $image = $request->signature;  // your base64 encoded
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = str_random(32) . '.' . 'jpg';

        if (!\File::exists(public_path('user-uploads/' . 'contract/sign'))) {
            $result = \File::makeDirectory(public_path('user-uploads/contract/sign'), 0775, true);
        }

        \File::put(public_path() . '/user-uploads/contract/sign/' . $imageName, base64_decode($image));

        $sign->signature = $imageName;
        $sign->save();

        return Reply::redirect(route('member.contracts.show', md5($this->contract->id)));
    }

    public function editDiscussion($id)
    {
        $this->discussion = ContractDiscussion::find($id);
        return view('member.contracts.edit-discussion', $this->data);
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

    public function copy($id)
    {
        $this->clients = User::allClients();
        $this->contractType = ContractType::all();
        $this->contract = Contract::with('signature', 'renew_history', 'renew_history.renewedBy')->find($id);
        return view('member.contracts.copy', $this->data);
    }

    public function copySubmit(StoreRequest $request)
    {
        $contract  = new Contract();
        $contract->client_id = $request->client;
        $contract->subject = $request->subject;
        $contract->amount = $request->amount;
        $contract->original_amount = $request->amount;
        $contract->contract_type_id = $request->contract_type;
        $contract->start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $contract->original_start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $contract->end_date = Carbon::parse($request->end_date)->format('Y-m-d');
        $contract->original_end_date = Carbon::parse($request->end_date)->format('Y-m-d');
        $contract->description = $request->description;

        if ($request->contract_detail) {
            $contract->contract_detail = $request->contract_detail;
        }

        $contract->save();

        return Reply::redirect(route('member.contracts.edit', $contract->id), __('messages.contractAdded'));
    }
}

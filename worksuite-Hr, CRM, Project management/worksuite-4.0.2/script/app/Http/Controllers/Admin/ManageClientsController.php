<?php

namespace App\Http\Controllers\Admin;

use App\ClientDetails;
use App\Country;
use App\DataTables\Admin\ClientsDataTable;
use App\Helper\Reply;
use App\Http\Requests\Admin\Client\StoreClientRequest;
use App\Http\Requests\Admin\Client\UpdateClientRequest;
use App\Http\Requests\Gdpr\SaveConsentUserDataRequest;
use App\Invoice;
use App\Lead;
use App\Payment;
use App\PurposeConsent;
use App\PurposeConsentUser;
use App\UniversalSearch;
use App\User;
use App\ClientCategory;
use App\ClientSubCategory;
use App\Contract;
use App\ContractType;
use App\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ManageClientsController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.clients';
        $this->pageIcon = 'icon-people';
        $this->middleware(function ($request, $next) {
            if (!in_array('clients', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ClientsDataTable $dataTable)
    {
        if (!request()->ajax()) {
            $this->subcategories = ClientSubCategory::all();
            $this->categories = ClientCategory::all();
            $this->clients = User::allClients();
            $this->projects = Project::all();
            $this->contracts = ContractType::all();
            $this->countries = Country::all();

            $this->totalClients = count($this->clients);
        }

        return $dataTable->render('admin.clients.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($leadID = null)
    {
        if ($leadID) {
            $this->leadDetail = Lead::findOrFail($leadID);
        }
        $this->countries = Country::all();

        $client = new ClientDetails();
        $this->categories = ClientCategory::all();
        $this->subcategories = ClientSubCategory::all();
        $this->fields = $client->getCustomFieldGroupsWithFields()->fields;

        if (request()->ajax()) {
            return view('admin.clients.ajax-create', $this->data);
        }

        return view('admin.clients.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientRequest $request)
    {
        $data = $request->all();
        $data['password'] = Hash::make($request->input('password'));

        unset($data['phone_code']);
        $data['country_id'] = $request->input('phone_code');
        $data['name'] = $request->input('salutation')." ".$request->input('name');
        $data['category_id'] = $request->input('category_id');
        $data['sub_category_id'] = $request->input('sub_category_id');
        $user = User::create($data);
        $user->client_details()->create($data);

        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $client = $user->client_details;
            $client->updateCustomFieldData($request->get('custom_fields_data'));
        }

        $user->attachRole(3);

        cache()->forget('all-clients');

        //log search
        $this->logSearchEntry($user->id, $user->name, 'admin.clients.edit', 'client');
        $this->logSearchEntry($user->id, $user->email, 'admin.clients.edit', 'client');
        if (!is_null($user->client_details->company_name)) {
            $this->logSearchEntry($user->id, $user->client_details->company_name, 'admin.clients.edit', 'client');
        }

        if ($request->has('lead')) {
            $lead = Lead::findOrFail($request->lead);
            $lead->client_id = $user->id;
            $lead->save();

            return Reply::redirect(route('admin.leads.index'), __('messages.leadClientChangeSuccess'));
        }

        if ($request->has('ajax_create')) {
            $teams = User::allClients();
            $teamData = '';

            foreach ($teams as $team) {
                $teamData .= '<option value="' . $team->id . '"> ' . ucwords($team->name) . ' </option>';
            }

            return Reply::successWithData(__('messages.clientAdded'), ['teamData' => $teamData]);
        }


        return Reply::redirect(route('admin.clients.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->categories = ClientCategory::all();
        $this->subcategories = ClientSubCategory::all();
        $this->client = User::withoutGlobalScope('active')->findOrFail($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->client->id)->first();
        $this->clientStats = $this->clientStats($id);

        if (!is_null($this->clientDetail)) {
            $this->clientDetail = $this->clientDetail->withCustomFields();
            $this->fields = $this->clientDetail->getCustomFieldGroupsWithFields()->fields;
        }
        return view('admin.clients.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->userDetail = User::withoutGlobalScope('active')->findOrFail($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->userDetail->id)->first();
        $this->countries = Country::all();
        $this->categories = ClientCategory::all();
        $this->subcategories = ClientSubCategory::all();
        if (!is_null($this->clientDetail)) {
            $this->clientDetail = $this->clientDetail->withCustomFields();
            $this->fields = $this->clientDetail->getCustomFieldGroupsWithFields()->fields;
        }
        return view('admin.clients.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateClientRequest $request, $id)
    {
        $user = User::withoutGlobalScope('active')->findOrFail($id);
        $data =  $request->all();

        unset($data['password']);
        unset($data['phone_code']);
        if ($request->password != '') {
            $data['password'] = Hash::make($request->input('password'));
        }
        $data['country_id'] = $request->input('phone_code');
        $user->update($data);

        if ($user->client_details) {
            $data['category_id'] = $request->input('category_id');
             $data['sub_category_id'] = $request->input('sub_category_id');
            $fields = $request->only($user->client_details->getFillable());
            $user->client_details->fill($fields);
            $user->client_details->save();
        } else {
            $user->client_details()->create($data);
        }


        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $user->client_details->updateCustomFieldData($request->get('custom_fields_data'));
        }
        return Reply::redirect(route('admin.clients.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $universalSearches = UniversalSearch::where('searchable_id', $id)->where('module_type', 'client')->get();
        if ($universalSearches) {
            foreach ($universalSearches as $universalSearch) {
                UniversalSearch::destroy($universalSearch->id);
            }
        }
        User::destroy($id);
        return Reply::success(__('messages.clientDeleted'));
    }

    public function showProjects($id)
    {
        $this->client = User::with('projects')->withoutGlobalScope('active')->findOrFail($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->client->id)->first();
        $this->clientStats = $this->clientStats($id);

        if (!is_null($this->clientDetail)) {
            $this->clientDetail = $this->clientDetail->withCustomFields();
            $this->fields = $this->clientDetail->getCustomFieldGroupsWithFields()->fields;
        }

        return view('admin.clients.projects', $this->data);
    }

    public function showInvoices($id)
    {
        $this->client = User::withoutGlobalScope('active')->findOrFail($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->client->id)->first();
        $this->clientStats = $this->clientStats($id);

        if (!is_null($this->clientDetail)) {
            $this->clientDetail = $this->clientDetail->withCustomFields();
            $this->fields = $this->clientDetail->getCustomFieldGroupsWithFields()->fields;
        }

        $this->invoices = Invoice::leftJoin('projects', 'projects.id', '=', 'invoices.project_id')
            ->join('currencies', 'currencies.id', '=', 'invoices.currency_id')
            ->selectRaw('invoices.invoice_number, invoices.total, currencies.currency_symbol, invoices.issue_date, invoices.id,invoices.credit_note, invoices.status,
            ( select payments.amount from payments where invoice_id = invoices.id) as paid_payment')
            ->where(function ($query) use ($id) {
                $query->where('projects.client_id', $id)
                    ->orWhere('invoices.client_id', $id);
            })
            ->orderBy('invoices.id', 'desc')
            ->get();
        return view('admin.clients.invoices', $this->data);
    }

    public function showPayments($id)
    {
        $this->client = User::withoutGlobalScope('active')->findOrFail($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->client->id)->first();
        $this->clientStats = $this->clientStats($id);

        if (!is_null($this->clientDetail)) {
            $this->clientDetail = $this->clientDetail->withCustomFields();
            $this->fields = $this->clientDetail->getCustomFieldGroupsWithFields()->fields;
        }

        $this->payments = Payment::with(['project:id,project_name', 'currency:id,currency_symbol,currency_code', 'invoice'])
            ->leftJoin('invoices', 'invoices.id', '=', 'payments.invoice_id')
            ->leftJoin('projects', 'projects.id', '=', 'payments.project_id')
            ->select('payments.id', 'payments.project_id', 'payments.currency_id', 'payments.invoice_id', 'payments.amount', 'payments.status', 'payments.paid_on', 'payments.remarks')
            ->where('payments.status', '=', 'complete')
            ->where(function ($query) use ($id) {
                $query->where('projects.client_id', $id)
                    ->orWhere('invoices.client_id', $id);
            })
            ->orderBy('payments.id', 'desc')
            ->get();
        return view('admin.clients.payments', $this->data);
    }
    
    public function gdpr($id)
    {
        $this->client = User::withoutGlobalScope('active')->findOrFail($id);
        $this->clientStats = $this->clientStats($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->client->id)->first();
        $this->allConsents = PurposeConsent::with(['user' => function ($query) use ($id) {
            $query->where('client_id', $id)
            ->orderBy('created_at', 'desc');
        }])->get();
        
        // dd('test');
        return view('admin.clients.gdpr', $this->data);
    }

    public function consentPurposeData($id)
    {
        $purpose = PurposeConsentUser::select('purpose_consent.name', 'purpose_consent_users.created_at', 'purpose_consent_users.status', 'purpose_consent_users.ip', 'users.name as username', 'purpose_consent_users.additional_description')
            ->join('purpose_consent', 'purpose_consent.id', '=', 'purpose_consent_users.purpose_consent_id')
            ->leftJoin('users', 'purpose_consent_users.updated_by_id', '=', 'users.id')
            ->where('purpose_consent_users.client_id', $id);

        // dd($purpose->first()->created_at);

        return DataTables::of($purpose)
            ->editColumn('status', function ($row) {
                if ($row->status == 'agree') {
                    $status = __('modules.gdpr.optIn');
                } else if ($row->status == 'disagree') {
                    $status = __('modules.gdpr.optOut');
                } else {
                    $status = '';
                }

                return $status;
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? $row->created_at->format($this->global->date_format) : '-';
            })
            ->make(true);
    }

    public function saveConsentLeadData(SaveConsentUserDataRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $consent = PurposeConsent::findOrFail($request->consent_id);

        if ($request->consent_description && $request->consent_description != '') {
            $consent->description = $request->consent_description;
            $consent->save();
        }

        // Saving Consent Data
        $newConsentLead = new PurposeConsentUser();
        $newConsentLead->client_id = $user->id;
        $newConsentLead->purpose_consent_id = $consent->id;
        $newConsentLead->status = trim($request->status);
        $newConsentLead->ip = $request->ip();
        $newConsentLead->updated_by_id = $this->user->id;
        $newConsentLead->additional_description = $request->additional_description;
        $newConsentLead->save();

        $url = route('admin.clients.gdpr', $user->id);

        return Reply::redirect($url);
    }

    public function clientStats($id)
    {
        return DB::table('users')
            ->select(
                DB::raw('(select count(projects.id) from `projects` WHERE projects.client_id = '.$id.') as totalProjects'),
                DB::raw('(select count(invoices.id) from `invoices` left join projects on projects.id=invoices.project_id WHERE invoices.status != "paid" and invoices.status != "canceled" and (projects.client_id = '.$id.' or invoices.client_id = '.$id.')) as totalUnpaidInvoices'),
                DB::raw('(select sum(payments.amount) from `payments` left join projects on projects.id=payments.project_id WHERE payments.status = "complete" and projects.client_id = '.$id.') as projectPayments'),
                DB::raw('(select sum(payments.amount) from `payments` inner join invoices on invoices.id=payments.invoice_id  WHERE payments.status = "complete" and invoices.client_id = '.$id.') as invoicePayments'),
                DB::raw('(select count(contracts.id) from `contracts` WHERE contracts.client_id = '.$id.') as totalContracts')
            )
            ->first();
    }
}

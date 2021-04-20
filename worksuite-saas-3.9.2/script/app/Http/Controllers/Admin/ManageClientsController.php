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
use App\Helper\Files;
use App\Notifications\NewUser;
use App\Payment;
use App\PurposeConsent;
use App\PurposeConsentUser;
use App\Role;
use App\Scopes\CompanyScope;
use App\UniversalSearch;
use App\User;
use App\Project;
use App\Contract;
use App\Notes;
use App\ContractType;
use App\ClientCategory;
use App\ClientSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class ManageClientsController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.clients';
        $this->pageIcon = 'icon-people';
        $this->middleware(function ($request, $next) {
            abort_if(!in_array('clients', $this->user->modules),403);
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
        $this->clients = User::allClients();
        $this->totalClients = count($this->clients);
        $this->categories = ClientCategory::all();
        $this->projects = Project::all();
        $this->contracts = ContractType::all();
        $this->countries = Country::all();
        $this->subcategories = ClientSubCategory::all();
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

        $client = new ClientDetails();
        $this->categories = ClientCategory::all();
        $this->subcategories = ClientSubCategory::all();
        $this->fields = $client->getCustomFieldGroupsWithFields()->fields;
        $this->countries = Country::all();

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
        $isSuperadmin = User::withoutGlobalScopes(['active', CompanyScope::class])->where('super_admin', '1')->where('email', $request->input('email'))->get()->count();
        if ($isSuperadmin > 0) {
            return Reply::error(__('messages.superAdminExistWithMail'));
        }

        $existing_user = User::withoutGlobalScopes(['active', CompanyScope::class])->select('id', 'email')->where('email', $request->input('email'))->first();
        // if no user found create new user with random password
        if (!$existing_user) {
            $password = str_random(8);
            // create new user
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = Hash::make($password);
            $user->mobile = $request->input('phone_code').' '.$request->input('mobile');
            $user->country_id = $request->input('phone_code');
            if($request->input('locale')!= ''){
                $user->locale = $request->input('locale');
            }else{
                $user->locale = company()->locale;

            }
            $user->save();

            // attach role
            $role = Role::where('name', 'client')->first();
            $user->attachRole($role->id);

            if ($request->has('lead')) {
                $lead = Lead::findOrFail($request->lead);
                $lead->client_id = $user->id;
                $lead->save();
            }
        }

        $existing_client_count = ClientDetails::select('id', 'email', 'company_id')
            ->where(
                [
                    'email' => $request->input('email')
                ]
            )->count();

        if ($existing_client_count === 0) {
            $client = new ClientDetails();
            $client->user_id = $existing_user ? $existing_user->id : $user->id;
            $client->name = $request->salutation." ".$request->input('name');
            $client->email = $request->input('email');
            $client->mobile = $request->input('phone_code').' '.$request->input('mobile');
            $client->office_phone = $request->input('office_phone');
            $client->city = $request->input('city');
            $client->state = $request->input('state');
            $client->postal_code = $request->input('postal_code');
            $client->country_id = $request->country_id;
            $client->category_id = ($request->input('category_id') != 0 && $request->input('category_id') != '') ? $request->input('category_id') : null ;
            $client->sub_category_id = ($request->input('sub_category_id') != 0 && $request->input('sub_category_id') != '') ? $request->input('sub_category_id') : null ;
            $client->company_name = $request->company_name;
            $client->address = $request->address;
            $client->website = $request->website;
            $client->note = $request->note;
            $client->skype = $request->skype;
            $client->facebook = $request->facebook;
            $client->twitter = $request->twitter;
            $client->linkedin = $request->linkedin;
            $client->gst_number = $request->gst_number;
            $client->shipping_address = $request->shipping_address;
            if ($request->has('email_notifications')) {
                $client->email_notifications = $request->email_notifications;
            }
            $client->save();

            // attach role
            if ($existing_user) {
                $role = Role::where('name', 'client')->where('company_id', $client->company_id)->first();
                $existing_user->attachRole($role->id);
            }

            // To add custom fields data
            if ($request->get('custom_fields_data')) {
                $client->updateCustomFieldData($request->get('custom_fields_data'));
            }

            // log search
            if (!is_null($client->company_name)) {
                $user_id = $existing_user ? $existing_user->id : $user->id;
                $this->logSearchEntry($user_id, $client->company_name, 'admin.clients.edit', 'client');
            }
            //log search
            $this->logSearchEntry($client->id, $request->name, 'admin.clients.edit', 'client');
            $this->logSearchEntry($client->id, $request->email, 'admin.clients.edit', 'client');
        } else {
            return Reply::error('Provided email is already registered. Try with different email.');
        }

        if (!$existing_user && $request->sendMail == 'yes') {
            //send welcome email notification
            $user->notify(new NewUser($password));
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
        $this->client = User::findClient($id);
        $this->categories = ClientCategory::all();
        $this->subcategories = ClientSubCategory::all();
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
        $this->userDetail = ClientDetails::join('users', 'client_details.user_id', '=', 'users.id')
            ->where('client_details.id', $id)
            ->select('client_details.id', 'client_details.name', 'client_details.email', 'client_details.user_id', 'client_details.mobile', 'users.locale', 'users.status', 'users.login')
            ->first();

        $this->clientDetail = ClientDetails::where('user_id', '=', $this->userDetail->user_id)->first();

        if (!is_null($this->clientDetail)) {
            $this->clientDetail = $this->clientDetail->withCustomFields();
            $this->fields = $this->clientDetail->getCustomFieldGroupsWithFields()->fields;
        }
        $this->countries = Country::all();
        $this->categories = ClientCategory::all();
        $this->subcategories = ClientSubCategory::all();

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
        $client = ClientDetails::find($id);

        $client->company_name = $request->company_name;
        $client->name = $request->input('name');
        $client->email = $request->input('email');
        $client->mobile = $request->input('phone_code').' '.$request->input('mobile');
        $client->country_id = $request->input('country_id');
        $client->address = $request->address;
        $client->office_phone = $request->input('office_phone');
        $client->city = $request->input('city');
        $client->state = $request->input('state');
        $client->postal_code = $request->input('postal_code');
        $client->category_id = ($request->input('category_id') != 0 && $request->input('category_id') != '') ? $request->input('category_id') : null ;
        $client->sub_category_id = ($request->input('sub_category_id') != 0 && $request->input('sub_category_id') != '') ? $request->input('sub_category_id') : null ;
        $client->website = $request->website;
        $client->note = $request->note;
        $client->skype = $request->skype;
        $client->facebook = $request->facebook;
        $client->twitter = $request->twitter;
        $client->linkedin = $request->linkedin;
        $client->gst_number = $request->gst_number;
        $client->shipping_address = $request->shipping_address;
        $client->email_notifications = $request->email_notifications;
        $client->save();
        $user = User::withoutGlobalScope('active')->findOrFail($client->user_id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->password != '') {
            $user->password = Hash::make($request->input('password'));
        }
        if ($request->hasFile('image')) {
            $user->image = Files::upload($request->image, 'avatar', 300);
        }

        $user->save();
        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $client->updateCustomFieldData($request->get('custom_fields_data'));
        }

        $user = User::withoutGlobalScopes(['active', CompanyScope::class])->findOrFail($client->user_id);

        if ($request->password != '') {
            $user->password = Hash::make($request->input('password'));
        }
        $user->locale = $request->locale;
        $user->save();

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
        DB::beginTransaction();
        $clients_count = ClientDetails::withoutGlobalScope(CompanyScope::class)->where('user_id', $id)->count();
        if ($clients_count > 1) {
            $client_builder = ClientDetails::where('user_id', $id);
            $client = $client_builder->first();

            $user_builder = User::where('id', $id);
            $user = $user_builder->first();
            if ($user) {
                $other_client = $client_builder->withoutGlobalScope(CompanyScope::class)
                    ->where('company_id', '!=', $client->company_id)
                    ->first();

                request()->request->add(['company_id' => $other_client->company_id]);

                $user->save();
            }
            $role = Role::where('name', 'client')->first();
            $user_role = $user_builder->withoutGlobalScope(CompanyScope::class)->first();
            $user_role->detachRoles([$role->id]);
            $universalSearches = UniversalSearch::where('searchable_id', $id)->where('module_type', 'client')->get();
            if ($universalSearches) {
                foreach ($universalSearches as $universalSearch) {
                    UniversalSearch::destroy($universalSearch->id);
                }
            }
            $client->delete();
        } else {
            // $client = ClientDetails::where('user_id', $id)->first();
            // $client->delete();
            $universalSearches = UniversalSearch::where('searchable_id', $id)->where('module_type', 'client')->get();
            if ($universalSearches) {
                foreach ($universalSearches as $universalSearch) {
                    UniversalSearch::destroy($universalSearch->id);
                }
            }
            $userRoles = User::withoutGlobalScopes([CompanyScope::class, 'active'])->where('id', $id)->first()->role->count();
            if($userRoles > 1){
                $role = Role::where('name', 'client')->first();
                $client_role = User::withoutGlobalScopes([CompanyScope::class, 'active'])->where('id', $id)->first();
                $client_role->detachRoles([$role->id]);
                ClientDetails::withoutGlobalScope(CompanyScope::class)->where('user_id', $id)->delete();
            }
            else{
                User::withoutGlobalScopes([CompanyScope::class, 'active'])->where('id', $id)->delete($id);
            }
        }
        DB::commit();
        return Reply::success(__('messages.clientDeleted'));
    }

    public function showProjects($id)
    {
        $this->client = User::findClient($id);

        if (!$this->client) {
            abort(404);
        }

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
        $this->client = User::findClient($id);

        if (!$this->client) {
            abort(404);
        }

        $this->clientDetail = $this->client ? $this->client->client_details : abort(404);
        $this->clientStats = $this->clientStats($id);

        if (!is_null($this->clientDetail)) {
            $this->clientDetail = $this->clientDetail->withCustomFields();
            $this->fields = $this->clientDetail->getCustomFieldGroupsWithFields()->fields;
        }

        $this->invoices = Invoice::selectRaw('invoices.invoice_number, invoices.total, currencies.currency_symbol, invoices.issue_date, invoices.id,
            ( select payments.amount from payments where invoice_id = invoices.id) as paid_payment')
            ->leftJoin('projects', 'projects.id', '=', 'invoices.project_id')
            ->join('currencies', 'currencies.id', '=', 'invoices.currency_id')
            ->where(function ($query) use ($id) {
                $query->where('projects.client_id', $id)
                    ->orWhere('invoices.client_id', $id);
            })
            ->get();


        return view('admin.clients.invoices', $this->data);
    }

    public function showPayments($id)
    {
        $this->client = User::findClient($id);
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
    // public function showNotes($id){
    //     $this->clients = User::allClients();
    //     $this->employees = User::allEmployees();

    //     $this->notes = Notes::where('client_id',$id)->get();
    //      $this->client = User::findClient($id);
    //     $this->clientDetail = ClientDetails::where('user_id', '=', $this->client->id)->first();
    //     $this->clientStats = $this->clientStats($id);

    //     return view('admin.clients.notes', $this->data);
    // }

    public function gdpr($id)
    {
        $this->client = User::findClient($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->client->id)->first();
        $this->allConsents = PurposeConsent::with(['user' => function ($query) use ($id) {
            $query->where('client_id', $id)
                ->orderBy('created_at', 'desc');
        }])->get();

        return view('admin.clients.gdpr', $this->data);
    }

    public function consentPurposeData($id)
    {
        $purpose = PurposeConsentUser::select('purpose_consent.name', 'purpose_consent_users.created_at', 'purpose_consent_users.status', 'purpose_consent_users.ip', 'users.name as username', 'purpose_consent_users.additional_description')
            ->join('purpose_consent', 'purpose_consent.id', '=', 'purpose_consent_users.purpose_consent_id')
            ->leftJoin('users', 'purpose_consent_users.updated_by_id', '=', 'users.id')
            ->where('purpose_consent_users.client_id', $id);

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
            
                return $row->created_at->format($this->global->date_format);
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
                DB::raw('(select count(projects.id) from `projects` WHERE projects.client_id = ' . $id . ' and projects.company_id = ' . company()->id . ') as totalProjects'),
                DB::raw('(select count(invoices.id) from `invoices` left join projects on projects.id=invoices.project_id WHERE invoices.status != "paid" and invoices.status != "canceled" and (projects.client_id = ' . $id . ' or invoices.client_id = ' . $id . ') and invoices.company_id = ' . company()->id . ') as totalUnpaidInvoices'),
                DB::raw('(select sum(payments.amount) from `payments` left join projects on projects.id=payments.project_id WHERE payments.status = "complete" and projects.client_id = ' . $id . ' and payments.company_id = ' . company()->id . ') as projectPayments'),
                DB::raw('(select sum(payments.amount) from `payments` inner join invoices on invoices.id=payments.invoice_id  WHERE payments.status = "complete" and invoices.client_id = ' . $id . ' and payments.company_id = ' . company()->id . ') as invoicePayments'),
                DB::raw('(select count(contracts.id) from `contracts` WHERE contracts.client_id = ' . $id . ' and contracts.company_id = ' . company()->id . ') as totalContracts')
            )
            ->first();
    }
    public function getSubcategory(Request $request){
        $this->subcategories = ClientSubCategory::where('category_id', $request->cat_id)->get();

        return Reply::dataOnly(['subcategory' => $this->subcategories]);
    }
}

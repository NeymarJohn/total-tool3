<?php

namespace App\Http\Controllers;

use App\ClientDetails;
use App\CreditNotes;
use App\Http\Requests\TicketForm\StoreTicket;
use App\Invoice;
use App\InvoiceItems;
use App\InvoiceSetting;
use App\OfflinePaymentMethod;
use App\Payment;
use App\PaymentGatewayCredentials;
use App\Project;
use App\Proposal;
use App\ProposalItem;
use App\ProposalSign;
use App\Task;
use App\Ticket;
use App\TicketCustomForm;
use App\TicketReply;
use App\TicketType;
use App\UniversalSearch;
use Froiden\RestAPI\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Helper\Reply;
use App\Http\Requests\Lead\StorePublicLead;
use App\Http\Requests\Lead\StoreRequest;
use App\Lead;
use App\LeadCustomForm;
use App\LeadStatus;
use App\PusherSetting;
use App\Setting;
use App\TaskboardColumn;
use App\TaskFile;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Hash;
use Nwidart\Modules\Facades\Module;
use Stripe\Stripe;
use GuzzleHttp\Client;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function login()
    {
        return redirect(route('login'));
    }

    public function invoice($id)
    {
        $this->pageTitle = 'app.menu.invoices';
        $this->pageIcon = 'icon-money';

        $this->invoice = Invoice::with('currency', 'project', 'project.client')->whereRaw('md5(id) = ?', $id)->firstOrFail();
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

        $this->settings = Setting::organisationSetting();
        $this->credentials = PaymentGatewayCredentials::first();
        $this->methods = OfflinePaymentMethod::activeMethod();
        $this->invoiceSetting = InvoiceSetting::first();

        return view('invoice', [
            'companyName' => $this->settings->company_name,
            'pageTitle' => $this->pageTitle,
            'pageIcon' => $this->pageIcon,
            'global' => $this->settings,
            'setting' => $this->settings,
            'settings' => $this->settings,
            'invoice' => $this->invoice,
            'paidAmount' => $this->paidAmount,
            'discount' => $this->discount,
            'credentials' => $this->credentials,
            'taxes' => $this->taxes,
            'methods' => $this->methods,
            'invoiceSetting' => $this->invoiceSetting,
        ]);
    }

    public function stripeModal(Request $request){
        $id = $request->invoice_id;
        $this->invoice = Invoice::with(['client', 'project', 'project.client'])->findOrFail($id);

        $this->settings = Setting::organisationSetting();
        $this->credentials = PaymentGatewayCredentials::first();
        $this->intent = '';
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

        $view = view('stripe-payment', [
            'companyName' => $this->settings->company_name,
            'global' => $this->settings,
            'settings' => $this->settings,
            'invoice' => $this->invoice,
            'credentials' => $this->credentials,
            'intent' => $this->intent,
            'customerDetail' => $this->customerDetail,
        ])->render();

        return Reply::dataOnly(['view' => $view]);
    }

    public function app()
    {
        $setting = Setting::select('id', 'company_name')->first();

        return ['data' => $setting];
    }

    public function gantt($ganttProjectId)
    {
        $this->settings = cache()->remember(
            'global-setting',
            60 * 60 * 24,
            function () {
                return \App\Setting::first();
            }
        );
        $this->ganttProjectId = $ganttProjectId;
        $this->project = Project::whereRaw('md5(id) = ?', $ganttProjectId)->first();

        return view('gantt', [
            'ganttProjectId' => $this->ganttProjectId,
            'global' => $this->settings,
            'project' => $this->project
        ]);
    }

    public function ganttData($ganttProjectId)
    {

        $data = array();
        $links = array();

        $projects = Project::select('id', 'project_name', 'start_date', 'deadline', 'completion_percent')
            ->whereRaw('md5(id) = ?', $ganttProjectId)
            ->get();

        $id = 0; //count for gantt ids
        foreach ($projects as $project) {
            $id = $id + 1;
            $projectId = $id;

            // TODO::ProjectDeadline to do
            $projectDuration = 0;
            if ($project->deadline) {
                $projectDuration = $project->deadline->diffInDays($project->start_date);
            }

            $data[] = [
                'id' => $projectId,
                'text' => ucwords($project->project_name),
                'start_date' => $project->start_date->format('Y-m-d H:i:s'),
                'duration' => $projectDuration,
                'progress' => $project->completion_percent / 100,
                'color' => 'grey',
                'textColor' => 'white',
                'project_id' => $project->id
            ];

            $tasks = Task::projectTasks($project->id, null, "0");

            foreach ($tasks as $key => $task) {
                $id = $id + 1;

                $taskDuration = $task->due_date->diffInDays($task->start_date);
                $taskDuration = $taskDuration + 1;

                $color = $task->board_column->label_color;

                $data[] = [
                    'id' => $task->id,
                    'text' => ucfirst($task->heading),
                    'start_date' => (!is_null($task->start_date)) ? $task->start_date->format('Y-m-d') : $task->due_date->format('Y-m-d'),
                    'duration' => $taskDuration,
                    'parent' => $projectId,
                    'color' => $color,
                    'taskid' => $task->id
                ];

                $links[] = [
                    'id' => $id,
                    'source' => $task->dependent_task_id != '' ? $task->dependent_task_id : $projectId,
                    'target' => $task->id,
                    'type' => $task->dependent_task_id != '' ? 0 : 1
                ];
            }
        }

        $ganttData = [
            'data' => $data,
            'links' => $links
        ];

        return response()->json($ganttData);
    }


    public function taskDetail($id)
    {
        $this->settings = cache()->remember(
            'global-setting',
            60 * 60 * 24,
            function () {
                return \App\Setting::first();
            }
        );
        $this->task = Task::with('board_column', 'subtasks', 'project', 'users', 'files')->findOrFail($id);
        $view = view('task_detail', [
            'task' => $this->task,
            'global' => $this->settings,
        ])->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function taskFiles($id)
    {
        $this->taskFiles = TaskFile::where('task_id', $id)->get();
        return view('task-files', ['taskFiles' => $this->taskFiles]);
    }

    public function history($id)
    {
        $this->settings = cache()->remember(
            'global-setting',
            60 * 60 * 24,
            function () {
                return \App\Setting::first();
            }
        );
        $this->task = Task::with('board_column', 'history', 'history.board_column')->findOrFail($id);
        $view = view('admin.tasks.history', [
            'task' => $this->task,
            'global' => $this->settings,
        ])->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function taskboard(Request $request, $encrypt)
    {
        $this->global = cache()->remember(
            'global-setting',
            60 * 60 * 24,
            function () {
                return \App\Setting::first();
            }
        );

        try {
            $companyName = decrypt($encrypt);
            if ($companyName != $this->global->company_name) {
                abort(404);
            }
        } catch (DecryptException $e) {
            abort(404);
        }

        $this->pusherSettings = PusherSetting::first();
        $this->startDate = Carbon::today()->subDays(15)->format($this->global->date_format);
        $this->endDate = Carbon::today()->addDays(15)->format($this->global->date_format);
        $this->projects = Project::allProjects();
        $this->clients = User::allClients();
        $this->employees = User::allEmployees();


        return view('taskboard', [
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'projects' => $this->projects,
            'clients' => $this->clients,
            'employees' => $this->employees,
            'global' => $this->global,
            'pusherSettings' => $this->pusherSettings,
        ]);
    }

    public function taskBoardData(Request $request)
    {
        $this->global = cache()->remember(
            'global-setting',
            60 * 60 * 24,
            function () {
                return \App\Setting::first();
            }
        );
        if (request()->ajax()) {

            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();

            $boardColumns = TaskboardColumn::with(['tasks' => function ($q) use ($startDate, $endDate, $request) {
                $q->with(['subtasks', 'completedSubtasks', 'comments', 'users', 'project'])
                    ->leftJoin('projects', 'projects.id', '=', 'tasks.project_id')
                    ->leftJoin('users as client', 'client.id', '=', 'projects.client_id')
                    ->join('task_users', 'task_users.task_id', '=', 'tasks.id')
                    ->join('users', 'task_users.user_id', '=', 'users.id')
                    ->join('taskboard_columns', 'taskboard_columns.id', '=', 'tasks.board_column_id')
                    ->leftJoin('users as creator_user', 'creator_user.id', '=', 'tasks.created_by')
                    ->select('tasks.*')
                    ->groupBy('tasks.id');

                $q->where(function ($task) use ($startDate, $endDate) {
                    $task->whereBetween(DB::raw('DATE(tasks.`due_date`)'), [$startDate, $endDate]);

                    $task->orWhereBetween(DB::raw('DATE(tasks.`start_date`)'), [$startDate, $endDate]);
                });
                $q->whereNull('projects.deleted_at');

                if ($request->projectID != 0 && $request->projectID !=  null && $request->projectID !=  'all') {
                    $q->where('tasks.project_id', '=', $request->projectID);
                }

                if ($request->clientID != '' && $request->clientID !=  null && $request->clientID !=  'all') {
                    $q->where('projects.client_id', '=', $request->clientID);
                }

                if ($request->assignedTo != '' && $request->assignedTo !=  null && $request->assignedTo !=  'all') {
                    $q->where('task_users.user_id', '=', $request->assignedTo);
                }

                if ($request->assignedBY != '' && $request->assignedBY !=  null && $request->assignedBY !=  'all') {
                    $q->where('creator_user.id', '=', $request->assignedBY);
                }

                $q->where('tasks.is_private', '=', 0);
            }])->orderBy('priority', 'asc')->get();

            $this->boardColumns = $boardColumns;

            $this->startDate = $startDate;
            $this->endDate = $endDate;

            $view = view('taskboard_board_data', [
                'boardColumns' => $this->boardColumns,
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
                'global' => $this->global,
            ])->render();
            return Reply::dataOnly(['view' => $view]);
        }
    }

    public function taskShare($id)
    {
        $this->pageTitle = 'app.task';

        $this->settings = cache()->remember(
            'global-setting',
            60 * 60 * 24,
            function () {
                return \App\Setting::first();
            }
        );
        $this->task = Task::with('board_column', 'subtasks', 'project', 'users')->whereRaw('md5(id) = ?', $id)->firstOrFail();

        return view('task-share', [
            'task' => $this->task,
            'global' => $this->settings
        ]);
    }

    /**
     * custom lead form
     *
     * @return \Illuminate\Http\Response
     */
    public function leadForm()
    {
        $this->pageTitle = 'modules.lead.leadForm';
        $this->settings = cache()->remember(
            'global-setting',
            60 * 60 * 24,
            function () {
                return \App\Setting::first();
            }
        );
        $this->leadFormFields = LeadCustomForm::where('status', 'active')
            ->orderBy('field_order', 'asc')->get();

        return view('lead-form', [
            'pageTitle' => $this->pageTitle,
            'leadFormFields' => $this->leadFormFields,
            'global' => $this->settings
        ]);
    }

    /**
     * save lead
     *
     * @return \Illuminate\Http\Response
     */
    public function leadStore(StorePublicLead $request)
    {
        $setting = \App\Setting::with('currency')->first();

        if ($setting->ticket_form_google_captcha) {
            // Checking is google recaptcha is valid
            $gRecaptchaResponseInput = 'g-recaptcha-response';
            $gRecaptchaResponse = $request->{$gRecaptchaResponseInput};
            $validateRecaptcha = $this->validateGoogleRecaptcha($gRecaptchaResponse);

            if (!$validateRecaptcha) {
                return Reply::error(__('auth.recaptchaFailed'));
            }
        }

        $leadStatus = LeadStatus::where('default', '1')->first();
        $settings = cache()->remember(
            'global-setting',
            60 * 60 * 24,
            function () {
                return \App\Setting::first();
            }
        );

        $lead = new Lead();
        $lead->company_name = (request()->has('company_name') ? $request->company_name : '');
        $lead->website      = (request()->has('website') ? $request->website : '');
        $lead->address      = (request()->has('address') ? $request->address : '');
        $lead->client_name  = (request()->has('name') ? $request->name : '');
        $lead->client_email = (request()->has('email') ? $request->email : '');
        $lead->mobile       = (request()->has('mobile') ? $request->mobile : '');
        $lead->status_id    = $leadStatus->id;
        $lead->value        = 0;
        $lead->currency_id  = $settings->currency->id;
        $lead->note      = $settings->message;
        $lead->save();

        return Reply::success(__('messages.LeadAddedUpdated'));
    }

    /**
     * custom lead form
     *
     * @return \Illuminate\Http\Response
     */
    public function ticketForm()
    {
        $this->pageTitle = 'app.ticketForm';
        $this->ticketFormFields = TicketCustomForm::where('status', 'active')
            ->orderBy('field_order', 'asc')
            ->get();
        $this->types = TicketType::all();
        $this->settings = cache()->remember(
            'global-setting',
            60 * 60 * 24,
            function () {
                return \App\Setting::first();
            }
        );

        return view('embed-forms.ticket-form', [
            'pageTitle'         => $this->pageTitle,
            'ticketFormFields'  => $this->ticketFormFields,
            'global'            => $this->settings,
            'types'             => $this->types
        ]);
    }

    /**
     * save lead
     *
     * @return \Illuminate\Http\Response
     */
    public function ticketStore(StoreTicket $request)
    {
        $setting = \App\Setting::with('currency')->first();

        if ($setting->ticket_form_google_captcha) {
            // Checking is google recaptcha is valid
            $gRecaptchaResponseInput = 'g-recaptcha-response';
            $gRecaptchaResponse = $request->{$gRecaptchaResponseInput};
            $validateRecaptcha = $this->validateGoogleRecaptcha($gRecaptchaResponse);

            if (!$validateRecaptcha) {
                return Reply::error(__('auth.recaptchaFailed'));
            }
        }

        // $rules['g-recaptcha-response'] = 'required';
        $existing_user = User::withoutGlobalScopes(['active'])->select('id', 'email')->where('email', $request->input('email'))->first();
        $newUser = $existing_user;
        if (!$existing_user) {
            $password = str_random(8);
            // create new user
            $client = new User();
            $client->name           = $request->input('name');
            $client->email          = $request->input('email');
            $client->password       = Hash::make($password);
            $client->save();

            // attach role
            $client->attachRole(3);

            $clientDetail = new ClientDetails();
            $clientDetail->user_id      = $client->id;
            $clientDetail->save();

            //log search
            $this->logSearchEntry($client->id, $client->name, 'admin.clients.edit', 'client');
            $this->logSearchEntry($client->id, $client->email, 'admin.clients.edit', 'client');

            $newUser = $client;
        }

        // Create New Ticket
        $ticket = new Ticket();
        $ticket->subject        = (request()->has('ticket_subject') ? $request->ticket_subject : '');;
        $ticket->status         = 'open';
        $ticket->user_id        = $newUser->id;
        $ticket->type_id        = (request()->has('type') ? $request->type : null);
        $ticket->priority       = (request()->has('priority') ? $request->priority : 'medium');
        $ticket->save();

        //save first message
        $reply = new TicketReply();
        $reply->message     = (request()->has('message') ? $request->message : '');
        $reply->ticket_id   = $ticket->id;
        $reply->user_id     = $newUser->id;; //current logged in user
        $reply->save();

        return Reply::success(__('messages.ticketAddSuccess'));
    }

    public function validateGoogleRecaptcha($googleRecaptchaResponse)
    {
        $setting = \App\Setting::with('currency')->first();

        $client = new Client();
        $response = $client->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'form_params' =>
                    [
                        'secret' => $setting->google_recaptcha_secret,
                        'response' => $googleRecaptchaResponse,
                        'remoteip' => $_SERVER['REMOTE_ADDR']
                    ]
            ]
        );

        $body = json_decode((string) $response->getBody());

        return $body->success;
    }

    public function googleRecaptchaMessage()
    {
        throw ValidationException::withMessages([
            'g-recaptcha-response' => [trans('auth.recaptchaFailed')],
        ]);
    }

    public function logSearchEntry($searchableId, $title, $route, $type)
    {
        $search = new UniversalSearch();
        $search->searchable_id  = $searchableId;
        $search->title          = $title;
        $search->route_name     = $route;
        $search->module_type    = $type;
        $search->save();
    }

    public function installedModule()
    {
        $message = '';
        $plugins = Module::allEnabled();

        $applicationVersion = trim(
            preg_replace(
                '/\s\s+/',
                ' ',
                !file_exists(\File::get(public_path() . '/version.txt')) ?
                    \File::get(public_path() . '/version.txt') : '0'
            )
        );
        $enableModules = [];
        $enableModules['application'] = 'worksuite';
        $enableModules['version'] = $applicationVersion;
        $enableModules['worksuite'] = $applicationVersion;
        foreach ($plugins as $plugin) {
            $enableModules[$plugin->getName()] = trim(
                preg_replace(
                    '/\s\s+/',
                    ' ',
                    !file_exists(\File::get($plugin->getPath() . '/version.txt')) ?
                        \File::get($plugin->getPath() . '/version.txt') : '0'
                )
            );
        }

        if (((int) str_replace('.', '', $enableModules['RestAPI'])) < 110) {
            $message .= 'Please update Rest API module greater then 1.1.0 version';
        }

        if (((int) str_replace('.', '', $enableModules['worksuite'])) < 400) {
            $message .= 'Please update'.ucfirst(config('app.name')).' greater then 4.0.0 version';
        }

        $enableModules['message'] = $message;

        return ApiResponse::make('Plugin data fetched successfully', $enableModules);
    }

    public function proposal($id)
    {
        $this->pageTitle = __('app.proposal');
        $this->pageIcon  = 'icon-people';

        $this->proposal = Proposal::with(['items'])->whereRaw('md5(id) = ?', $id)->firstOrFail();
        $this->settings = $this->settings = Setting::organisationSetting();;
        session(['company' => $this->settings]);

        $this->discount = 0;
        $this->invoiceSetting = InvoiceSetting::first();
        $this->taxes = [];
        return view('proposal-front.proposal', [
            'proposal' => $this->proposal,
            'pageTitle' => $this->pageTitle,
            'pageIcon' => $this->pageIcon,
            'settings' => $this->settings,
            'taxes' => $this->taxes,
            'discount' => $this->discount,
            'invoiceSetting' => $this->invoiceSetting,
        ]);
    }

    public function proposalAction(Request $request, $id)
    {
        $this->proposal = Proposal::with(['items'])->whereRaw('md5(id) = ?', $id)->firstOrFail();

        return view('proposal-front.accept', [
            'proposal' => $this->proposal,
            'type' => $request->type
        ]);
    }

    public function proposalActionStore(Request $request, $id)
    {
        $this->proposal = Proposal::whereRaw('md5(id) = ?', $id)->firstOrFail();

        if (!$this->proposal) {
            return Reply::error('you are not authorized to access this.');
        }

        if($request->type == 'accept')
        {
            if($this->proposal->signature_approval == 1){
                $sign = new ProposalSign();
                $sign->full_name   = $request->name;
                $sign->proposal_id = $this->proposal->id;
                $sign->email       = $request->email;
                $sign->proposal_id = $this->proposal->id;

                $image     = $request->signature;  // your base64 encoded
                $image     = str_replace('data:image/png;base64,', '', $image);
                $image     = str_replace(' ', '+', $image);
                $imageName = str_random(32) . '.' . 'jpg';

                if (!\File::exists(public_path('user-uploads/' . 'proposal/sign'))) {
                    $result = \File::makeDirectory(public_path('user-uploads/proposal/sign'), 0775, true);
                }

                \File::put(public_path() . '/user-uploads/proposal/sign/' . $imageName, base64_decode($image));

                $sign->signature = $imageName;
                $sign->save();
            }

            $this->proposal->status = 'accepted';

        }
        elseif($request->status == 'accept')
        {

        }
        else{
            $this->proposal->client_comment = $request->comment;
            $this->proposal->status = 'declined';

        }
        $this->proposal->save();

        return Reply::success('Proposal accepted successfully');
    }

    public function domPdfObjectProposalDownload($id)
    {
        $this->proposal = Proposal::findOrFail($id);
        if ($this->proposal->discount > 0) {
            if ($this->proposal->discount_type == 'percent') {
                $this->discount = (($this->proposal->discount / 100) * $this->proposal->sub_total);
            } else {
                $this->discount = $this->proposal->discount;
            }
        } else {
            $this->discount = 0;
        }
        $this->taxes = ProposalItem::where('type', 'tax')
            ->where('proposal_id', $this->proposal->id)
            ->get();

        $items = ProposalItem::whereNotNull('taxes')
            ->where('proposal_id', $this->proposal->id)
            ->get();

        $taxList = array();

        foreach ($items as $item) {
            if ($this->proposal->discount > 0 && $this->proposal->discount_type == 'percent') {
                $item->amount = $item->amount - (($this->proposal->discount / 100) * $item->amount);
            }
            foreach (json_decode($item->taxes) as $tax) {
                $this->tax = ProposalItem::taxbyid($tax)->first();
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

        $this->settings = Setting::organisationSetting();

        $pdf = app('dompdf.wrapper');
        $this->company = Setting::organisationSetting();
        $this->global = Setting::with('currency')->first();;
        $this->invoiceSetting = InvoiceSetting::first();

        $pdf->getDomPDF()->set_option("enable_php", true);
        App::setLocale($this->invoiceSetting->locale);
        Carbon::setLocale($this->invoiceSetting->locale);
        $pdf->loadView('admin.proposals.proposal-pdf', $this->data);

        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(530, 820, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        $filename = 'proposal-' . $this->proposal->id;

        return [
            'pdf' => $pdf,
            'fileName' => $filename
        ];
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadProposal($id)
    {

        $this->proposal = Proposal::whereRaw('md5(id) = ?', $id)->first();
        $this->company = Setting::organisationSetting();
        App::setLocale(isset($this->company->locale) ? $this->company->locale : 'en');

        // Download file uploaded
        if ($this->proposal->file != null) {
            return response()->download(storage_path('app/public/proposal-files') . '/' . $this->proposal->file);
        }

        $pdfOption = $this->domPdfObjectProposalDownload($this->proposal->id);
        $pdf = $pdfOption['pdf'];
        $filename = $pdfOption['fileName'];

        return $pdf->download($filename . '.pdf');
    }

    public function domPdfObjectForDownload($id)
    {
        $this->invoice = Invoice::whereRaw('md5(id) = ?', $id)->firstOrFail();
        App::setLocale(isset($this->invoice->company->locale) ? $this->invoice->company->locale : 'en');
        $this->paidAmount = $this->invoice->getPaidAmount();
        $this->creditNote = 0;
        if ($this->invoice->credit_note) {
            $this->creditNote = CreditNotes::where('invoice_id', $id)
                ->select('cn_number')
                ->first();
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
            if ($this->invoice->discount > 0 && $this->invoice->discount_type == 'percent') {
                $item->amount = $item->amount - (($this->invoice->discount / 100) * $item->amount);
            }
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

        $this->settings = Setting::organisationSetting();
        $this->global = Setting::with('currency')->first();;
        $this->invoiceSetting = InvoiceSetting::first();

        $pdf = app('dompdf.wrapper');

        $this->payments = Payment::with(['offlineMethod'])->where('invoice_id', $this->invoice->id)->where('status', 'complete')->orderBy('paid_on', 'desc')->get();

        $pdf->getDomPDF()->set_option("enable_php", true);
        App::setLocale($this->invoiceSetting->locale);
        Carbon::setLocale($this->invoiceSetting->locale);
        $pdf->loadView('invoices.' . $this->invoiceSetting->template, $this->data);

        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(530, 820, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        $filename = $this->invoice->invoice_number;

        return [
            'pdf' => $pdf,
            'fileName' => $filename
        ];
    }

    public function downloadInvoice($id)
    {

        $this->invoice = Invoice::whereRaw('md5(id) = ?', $id)->firstOrFail();
        $this->invoiceSetting = InvoiceSetting::first();
        App::setLocale($this->invoiceSetting->locale);
        // Download file uploaded
        if ($this->invoice->file != null) {
            return response()->download(storage_path('app/public/invoice-files') . '/' . $this->invoice->file);
        }

        $pdfOption = $this->domPdfObjectForDownload($id);
        $pdf = $pdfOption['pdf'];
        $filename = $pdfOption['fileName'];

        return $pdf->download($filename . '.pdf');
    }
}

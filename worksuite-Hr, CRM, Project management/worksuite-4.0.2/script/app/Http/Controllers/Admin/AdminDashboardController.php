<?php

namespace App\Http\Controllers\Admin;

use App\Attendance;
use App\ClientDetails;
use App\Contract;
use App\ContractSign;
use App\DashboardWidget;
use App\DataTables\Admin\EstimatesDataTable;
use App\DataTables\Admin\ExpensesDataTable;
use App\DataTables\Admin\InvoicesDataTable;
use App\DataTables\Admin\PaymentsDataTable;
use App\DataTables\Admin\ProposalDataTable;
use App\Designation;
use App\EmployeeDetails;
use App\Estimate;
use App\Expense;
use App\Helper\Reply;
use App\Invoice;
use App\InvoiceItems;
use App\Lead;
use App\LeadFollowUp;
use App\LeadSource;
use App\LeadStatus;
use App\Leave;
use App\Payment;
use App\Project;
use App\ProjectActivity;
use App\ProjectMilestone;
use App\ProjectTimeLog;
use App\Proposal;
use App\Setting;
use App\Task;
use App\TaskboardColumn;
use App\Team;
use App\Ticket;
use App\Traits\CurrencyExchange;
use App\User;
use App\UserActivity;
use Carbon\Carbon;
use Exception;
use Froiden\Envato\Traits\AppBoot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminDashboardController extends AdminBaseController
{
    use CurrencyExchange, AppBoot;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.dashboard';
        $this->pageIcon = 'icon-speedometer';
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $taskBoardColumn = TaskboardColumn::all();

        $completedTaskColumn = $taskBoardColumn->filter(function ($value, $key) {
            return $value->slug == 'completed';
        })->first();

        $this->counts = DB::table('users')
            ->select(
                DB::raw('(select count(users.id) from `users` inner join role_user on role_user.user_id=users.id inner join roles on roles.id=role_user.role_id WHERE roles.name = "client") as totalClients'),
                DB::raw('(select count(users.id) from `users` inner join role_user on role_user.user_id=users.id inner join roles on roles.id=role_user.role_id WHERE roles.name = "employee" and users.status = "active") as totalEmployees'),
                DB::raw('(select count(projects.id) from `projects`) as totalProjects'),
                DB::raw('(select count(invoices.id) from `invoices` where status = "unpaid") as totalUnpaidInvoices'),
                DB::raw('(select sum(project_time_logs.total_minutes) from `project_time_logs`) as totalHoursLogged'),
                DB::raw('(select count(tasks.id) from `tasks` where tasks.board_column_id=' . $completedTaskColumn->id . ') as totalCompletedTasks'),
                DB::raw('(select count(tasks.id) from `tasks` where tasks.board_column_id != ' . $completedTaskColumn->id . ') as totalPendingTasks'),
                DB::raw('(select count(attendances.id) from `attendances` inner join users as atd_user on atd_user.id=attendances.user_id inner join role_user on role_user.user_id=atd_user.id inner join roles on roles.id=role_user.role_id WHERE roles.name = "employee" and DATE(attendances.clock_in_time) = CURDATE() and atd_user.status = "active") as totalTodayAttendance'),
                DB::raw('(select count(tickets.id) from `tickets` where (status="open" or status="pending") and deleted_at IS NULL) as totalUnResolvedTickets'),
                DB::raw('(select count(tickets.id) from `tickets` where (status="resolved" or status="closed") and deleted_at IS NULL) as totalResolvedTickets')
            )
            ->first();

        $timeLog = intdiv($this->counts->totalHoursLogged, 60) . ' ' . __('app.hrs') . ' ';

        if (($this->counts->totalHoursLogged % 60) > 0) {
            $timeLog .= ($this->counts->totalHoursLogged % 60) . ' ' . __('app.mins');
        }

        $this->counts->totalHoursLogged = $timeLog;

        $this->pendingTasks = Task::with('project')
            ->where('tasks.board_column_id', '<>', $completedTaskColumn->id)
            ->where(DB::raw('DATE(due_date)'), '<=', Carbon::now()->timezone($this->global->timezone)->format('Y-m-d'))
            ->orderBy('due_date', 'desc')
            ->select('tasks.*')
            ->limit(15)
            ->get();

        $this->pendingLeadFollowUps = Lead::with('followup')
            ->selectRaw('leads.id,leads.company_name, ( select followup.next_follow_up_date from lead_follow_up as followup where followup.lead_id = leads.id 
            and DATE(followup.next_follow_up_date) <= "'.Carbon::now()->timezone($this->global->timezone)->format('Y-m-d').'" ORDER BY followup.created_at desc limit 1 ) as follow_date')
            ->join('lead_follow_up', 'lead_follow_up.lead_id', 'leads.id')
            ->where(DB::raw('DATE(lead_follow_up.next_follow_up_date)'), '<=', Carbon::now()->timezone($this->global->timezone)->format('Y-m-d'))
            ->where('leads.next_follow_up', 'yes')
            ->groupBy('leads.id')
            ->get();

        $this->newTickets = Ticket::where('status', 'open')
            ->orderBy('id', 'desc')->get();

        $this->projectActivities = ProjectActivity::with('project')
            ->join('projects', 'projects.id', '=', 'project_activity.project_id')
            ->whereNull('projects.deleted_at')->select('project_activity.*')
            ->limit(15)->orderBy('project_activity.id', 'desc')->groupBy('project_activity.id')->get();

        $this->userActivities = UserActivity::with('user')->limit(15)->orderBy('id', 'desc')->get();

        // earning chart
        $this->fromDate = Carbon::now()->timezone($this->global->timezone)->subDays(30);
        $this->toDate = Carbon::now()->timezone($this->global->timezone);
        $invoices = DB::table('payments')
            ->join('currencies', 'currencies.id', '=', 'payments.currency_id')
            ->where('paid_on', '>=', $this->fromDate)
            ->where('paid_on', '<=', $this->toDate)
            ->where('payments.status', 'complete')
            ->groupBy('date')
            ->orderBy('paid_on', 'ASC')
            ->get([
                DB::raw('DATE_FORMAT(paid_on,"%Y-%m-%d") as date'),
                DB::raw('sum(amount) as total'),
                'currencies.currency_code',
                'currencies.is_cryptocurrency',
                'currencies.usd_price',
                'currencies.exchange_rate'
            ]);

        $chartData = array();
        foreach ($invoices as $chart) {
            if ($chart->currency_code != $this->global->currency->currency_code) {
                if ($chart->is_cryptocurrency == 'yes') {
                    if ($chart->exchange_rate == 0) {
                        if ($this->updateExchangeRates()) {
                            $usdTotal = ($chart->total * $chart->usd_price);
                            $chartData[] = ['date' => $chart->date, 'total' => floor($usdTotal / $chart->exchange_rate)];
                        }
                    } else {
                        $usdTotal = ($chart->total * $chart->usd_price);
                        $chartData[] = ['date' => $chart->date, 'total' => floor($usdTotal / $chart->exchange_rate)];
                    }
                } else {
                    if ($chart->exchange_rate == 0) {
                        if ($this->updateExchangeRates()) {
                            $chartData[] = ['date' => $chart->date, 'total' => floor($chart->total / $chart->exchange_rate)];
                        }
                    } else {
                        $chartData[] = ['date' => $chart->date, 'total' => floor($chart->total / $chart->exchange_rate)];
                    }
                }
            } else {
                $chartData[] = ['date' => $chart->date, 'total' => round($chart->total, 2)];
            }
        }

        $this->chartData = json_encode($chartData);
        $this->leaves = Leave::with('user', 'type')
            ->where('status', '<>', 'rejected')
            ->whereYear('leave_date', Carbon::now()->timezone($this->global->timezone)->format('Y'))
            ->get();

        $this->progressPercent = $this->progressbarPercent();
        $this->widgets = DashboardWidget::where('dashboard_type', 'admin-dashboard')->get();
        $this->activeWidgets = DashboardWidget::where('dashboard_type', 'admin-dashboard')
        ->where('status', 1)->get()->pluck('widget_name')->toArray();
        $this->isCheckScript();

        $exists = Storage::disk('storage')->exists('down');

        if ($exists && is_null($this->global->purchase_code)) {
            return redirect(route('verify-purchase'));
        }
        return view('admin.dashboard.index', $this->data);
    }

    private function progressbarPercent()
    {
        $totalItems = 4;
        $completedItem = 1;
        $progress = [];
        $progress['progress_completed'] = false;

        if ($this->global->company_email != 'company@email.com') {
            $completedItem++;
            $progress['company_setting_completed'] = true;
        }

        if ($this->smtpSetting->verified !== 0 || $this->smtpSetting->mail_driver == 'mail') {
            $progress['smtp_setting_completed'] = true;

            $completedItem++;
        }

        if ($this->user->email != 'admin@example.com') {
            $progress['profile_setting_completed'] = true;

            $completedItem++;
        }


        if ($totalItems == $completedItem) {
            $progress['progress_completed'] = true;
        }

        $this->progress = $progress;


        return ($completedItem / $totalItems) * 100;
    }

    public function widget(Request $request, $dashboardType)
    {
        $data = $request->all();
        unset($data['_token']);
        DashboardWidget::where('status', 1)->where('dashboard_type', $dashboardType)->update(['status' => 0]);

        foreach ($data as $key => $widget) {
            DashboardWidget::where('widget_name', $key)->where('dashboard_type', $dashboardType)->update(['status' => 1]);
        }

        return Reply::success(__('messages.updatedSuccessfully'));
    }
    // client Dashboard start
    public function clientDashboard(Request $request)
    {
        $this->pageTitle = 'app.clientDashboard';

        $this->fromDate = Carbon::now()->timezone($this->global->timezone)->subDays(30)->toDateString();
        $this->toDate = Carbon::now()->timezone($this->global->timezone)->toDateString();
        $this->widgets = DashboardWidget::where('dashboard_type', 'admin-client-dashboard')->get();
        $this->activeWidgets = DashboardWidget::where('dashboard_type', 'admin-client-dashboard')->where('status', 1)->get()->pluck('widget_name')->toArray();
        if (request()->ajax()) {
            if (!is_null($request->startDate) && $request->startDate != "null" && !is_null($request->endDate) && $request->endDate != "null") {
                $this->fromDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
                $this->toDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            }

            $this->totalClient = User::withoutGlobalScope('active')
                ->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->leftJoin('client_details', 'users.id', '=', 'client_details.user_id')
                ->where('roles.name', 'client')
                ->whereBetween(DB::raw('DATE(client_details.`created_at`)'), [$this->fromDate, $this->toDate])
                ->select('users.id')
                ->get()->count();
            $this->totalLead = Lead::whereBetween(DB::raw('DATE(`created_at`)'), [$this->fromDate, $this->toDate])
                ->get()
                ->count();

            $this->totalLeadConversions = Lead::whereBetween(DB::raw('DATE(`updated_at`)'), [$this->fromDate, $this->toDate])
                ->whereNotNull('client_id')
                ->get()
                ->count();

            $this->totalContractsGenerated = Contract::whereBetween(DB::raw('DATE(`created_at`)'), [$this->fromDate, $this->toDate])
                ->get()
                ->count();

            $this->totalContractsSigned = ContractSign::whereBetween(DB::raw('DATE(`created_at`)'), [$this->fromDate, $this->toDate])
                ->get()
                ->count();

            $this->recentLoginActivities = User::withoutGlobalScope('active')
                ->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->leftJoin('client_details', 'users.id', '=', 'client_details.user_id')
                ->where('roles.name', 'client')
                ->whereNotNull('last_login')
                ->whereBetween(DB::raw('DATE(client_details.`created_at`)'), [$this->fromDate, $this->toDate])
                ->select('users.id', 'users.name', 'users.last_login', 'client_details.company_name')
                ->limit(10)
                ->orderBy('users.last_login', 'desc')
                ->get();
            // dd($this->recentLoginActivities);
            $this->latestClient = User::withoutGlobalScope('active')
                ->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->leftJoin('client_details', 'users.id', '=', 'client_details.user_id')
                ->where('roles.name', 'client')
                ->whereBetween(DB::raw('DATE(client_details.`created_at`)'), [$this->fromDate, $this->toDate])
                ->select('users.id', 'users.name', 'users.created_at', 'client_details.company_name')
                ->limit(10)
                ->orderBy('users.created_at', 'Asc')
                ->get();


            // client wise earning chart

            $projects = Payment::join('currencies', 'currencies.id', '=', 'payments.currency_id')
                ->join('projects', 'projects.id', '=', 'payments.project_id')
                ->join('users', 'users.id', '=', 'projects.client_id')
                ->whereBetween(DB::raw('DATE(payments.`paid_on`)'), [$this->fromDate, $this->toDate])
                ->where('payments.status', 'complete')
                ->groupBy('date')
                ->orderBy('payments.paid_on', 'ASC')
                ->select(
                    DB::raw('DATE_FORMAT(payments.paid_on,"%Y-%m-%d") as date'),
                    DB::raw('sum(payments.amount) as total'),
                    'currencies.currency_code',
                    'currencies.is_cryptocurrency',
                    'currencies.usd_price',
                    'currencies.exchange_rate',
                    'users.name'
                );

            $invoices = Payment::join('currencies', 'currencies.id', '=', 'payments.currency_id')
                ->join('invoices', 'invoices.id', '=', 'payments.invoice_id')
                ->join('users', 'users.id', '=', 'invoices.client_id')
                ->whereBetween(DB::raw('DATE(payments.`paid_on`)'), [$this->fromDate, $this->toDate])
                ->where('payments.status', 'complete')
                ->groupBy('date')
                ->orderBy('payments.paid_on', 'ASC')
                ->select(
                    DB::raw('DATE_FORMAT(payments.paid_on,"%Y-%m-%d") as date'),
                    DB::raw('sum(payments.amount) as total'),
                    'currencies.currency_code',
                    'currencies.is_cryptocurrency',
                    'currencies.usd_price',
                    'currencies.exchange_rate',
                    'users.name'
                )
                ->union($projects)
                ->get();

            $chartData = array();
            $chartDataClients = array();
            foreach ($invoices as $chart) {
                if (!array_key_exists($chart->name, $chartDataClients)) {
                    $chartDataClients[$chart->name] = 0;
                }
                if ($chart->currency_code != $this->global->currency->currency_code) {
                    if ($chart->is_cryptocurrency == 'yes') {
                        if ($chart->exchange_rate == 0) {
                            if ($this->updateExchangeRates()) {
                                $usdTotal = ($chart->total * $chart->usd_price);
                                $chartDataClients[$chart->name] = $chartDataClients[$chart->name] + floor($usdTotal / $chart->exchange_rate);
                            }
                        } else {
                            $usdTotal = ($chart->total * $chart->usd_price);
                            $chartDataClients[$chart->name] = $chartDataClients[$chart->name] + floor($usdTotal / $chart->exchange_rate);
                        }
                    } else {
                        if ($chart->exchange_rate == 0) {
                            if ($this->updateExchangeRates()) {
                                $chartDataClients[$chart->name] = $chartDataClients[$chart->name] + floor($chart->total / $chart->exchange_rate);
                            }
                        } else {
                            $chartDataClients[$chart->name] = $chartDataClients[$chart->name] + floor($chart->total / $chart->exchange_rate);
                        }
                    }
                } else {
                    $chartDataClients[$chart->name] = $chartDataClients[$chart->name] + round($chart->total, 2);
                }
            }
            foreach ($chartDataClients as $key => $chartDataClient) {
                $chartData[] = ['client' => $key, 'total' => $chartDataClient];
            }

            $this->chartData = json_encode($chartData);

            // client wise timelogs

            $projectTimelogs = ProjectTimeLog::join('projects', 'projects.id', 'project_time_logs.project_id')
                ->join('users', 'users.id', 'projects.client_id')
                ->whereBetween(DB::raw('DATE(project_time_logs.`created_at`)'), [$this->fromDate, $this->toDate])
                ->where('project_time_logs.approved', 1)
                ->select('project_time_logs.*', 'users.name');

            $allTimelogs = ProjectTimeLog::join('tasks', 'tasks.id', 'project_time_logs.task_id')
                ->join('projects', 'projects.id', 'tasks.project_id')
                ->join('users', 'users.id', 'projects.client_id')
                ->whereBetween(DB::raw('DATE(project_time_logs.`created_at`)'), [$this->fromDate, $this->toDate])
                ->where('project_time_logs.approved', 1)
                ->select('project_time_logs.*', 'users.name')
                ->union($projectTimelogs)
                ->get();

            $clientWiseTimelogs = array();
            $clientWiseTimelogChartData = array();

            foreach ($allTimelogs as $timelog) {
                if (!array_key_exists($timelog->name, $clientWiseTimelogs)) {
                    $clientWiseTimelogs[$timelog->name] = 0;
                }
                $clientWiseTimelogs[$timelog->name] = $clientWiseTimelogs[$timelog->name] + $timelog->total_minutes;
            }
            foreach ($clientWiseTimelogs as $key => $clientWiseTimelog) {
                $totalTime = intdiv($clientWiseTimelog, 60);
                $clientWiseTimelogChartData[] = ['client' => $key, 'totalHours' => $totalTime];
            }
            $this->clientWiseTimelogChartData = json_encode($clientWiseTimelogChartData);

            // total lead vs status
            $leadVsStatus = array();
            $leadStatus = LeadStatus::get();
            foreach ($leadStatus as $status) {
                $leadCount = Lead::where('status_id', $status->id)
                    ->whereBetween(DB::raw('DATE(`created_at`)'), [$this->fromDate, $this->toDate])
                    ->get()
                    ->count();
                if ($leadCount > 0) {
                    $leadVsStatus[] = ['total' => $leadCount, 'label' => $status->type, 'color' => $status->label_color];
                }
            }
            $this->leadVsStatus = json_encode($leadVsStatus);
            // dd($this->leadVsStatus);
            // total lead vs source
            $leadVsSource = array();
            $leadSource = LeadSource::get();
            foreach ($leadSource as $source) {
                $leadCount = Lead::where('source_id', $source->id)
                    ->whereBetween(DB::raw('DATE(`created_at`)'), [$this->fromDate, $this->toDate])
                    ->get()
                    ->count();
                // dd($source->id);
                if ($leadCount > 0) {
                    $leadVsSource[] = ['total' => $leadCount, 'label' => $source->type];
                }
            }
            // dd($leadSource);
            $this->leadVsSource = json_encode($leadVsSource);
            $view = view('admin.dashboard.client-dashboard', $this->data)->render();
            return Reply::dataOnly(['view' => $view]);
        }
        return view('admin.dashboard.client', $this->data);
    }
    // client Dashboard end

    // finance Dashboard start
    public function financeDashboard(Request $request)
    {

        $this->pageTitle = 'app.financeDashboard';
        $this->fromDate = Carbon::now()->timezone($this->global->timezone)->subDays(30)->toDateString();
        $this->toDate = Carbon::now()->timezone($this->global->timezone)->toDateString();

        $this->widgets = DashboardWidget::where('dashboard_type', 'admin-finance-dashboard')->get();
        $this->activeWidgets = DashboardWidget::where('dashboard_type', 'admin-finance-dashboard')->where('status', 1)->get()->pluck('widget_name')->toArray();

        if (request()->ajax()) {
        }
        if (request()->ajax()) {
            if (!is_null($request->startDate) && $request->startDate != "null" && !is_null($request->endDate) && $request->endDate != "null") {
                $this->fromDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
                $this->toDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            }

            $this->startDate = $this->fromDate;
            $this->endDate = $this->toDate;

            // count of paid invoices
            $this->totalPaidInvoice = Invoice::where('status', 'paid')
                ->whereBetween(DB::raw('DATE(`created_at`)'), [$this->fromDate, $this->toDate])
                ->select('id')
                ->get()->count();

            // Total Expense
            $expenses = Expense::whereBetween(DB::raw('DATE(expenses.`created_at`)'), [$this->fromDate, $this->toDate])
                ->join('currencies', 'currencies.id', '=', 'expenses.currency_id')
                ->select(
                    'expenses.id',
                    'expenses.price',
                    'currencies.currency_code',
                    'currencies.is_cryptocurrency',
                    'currencies.usd_price',
                    'currencies.exchange_rate'
                )
                ->where('status', 'approved')
                ->get();
            $totalExpenses = 0;
            foreach ($expenses as $expense) {
                if ($expense->currency_code != $this->global->currency->currency_code) {
                    if ($expense->is_cryptocurrency == 'yes') {
                        if ($expense->exchange_rate == 0) {
                            if ($this->updateExchangeRates()) {
                                $usdTotal = ($expense->price * $expense->usd_price);
                                $totalExpenses += floor($usdTotal / $expense->exchange_rate);
                            }
                        } else {
                            $usdTotal = ($expense->price * $expense->usd_price);
                            $totalExpenses += floor($usdTotal / $expense->exchange_rate);
                        }
                    } else {
                        if ($expense->exchange_rate == 0) {
                            if ($this->updateExchangeRates()) {
                                $totalExpenses += floor($expense->price / $expense->exchange_rate);
                            }
                        } else {
                            $totalExpenses += floor($expense->price / $expense->exchange_rate);
                        }
                    }
                } else {
                    $totalExpenses += round($expense->price, 2);
                }
            }
            $this->totalExpenses = $totalExpenses;

            // Total Profit
            $paymentsModal = Payment::whereBetween(DB::raw('DATE(payments.`paid_on`)'), [$this->fromDate, $this->toDate]);

            $payments = clone $paymentsModal;

            $payments = $payments->join('currencies', 'currencies.id', '=', 'payments.currency_id')
                ->where('payments.status', 'complete')
                ->select(
                    DB::raw('sum(payments.amount) as total'),
                    'currencies.currency_code',
                    'currencies.is_cryptocurrency',
                    'currencies.usd_price',
                    'currencies.exchange_rate'
                )
                ->get();
            $totalEarnings = 0;
            foreach ($payments as $payment) {
                if ($payment->currency_code != $this->global->currency->currency_code) {
                    if ($payment->is_cryptocurrency == 'yes') {
                        if ($payment->exchange_rate == 0) {
                            if ($this->updateExchangeRates()) {
                                $usdTotal = ($payment->total * $payment->usd_price);
                                $totalEarnings += floor($usdTotal / $payment->exchange_rate);
                            }
                        } else {
                            $usdTotal = ($payment->total * $payment->usd_price);
                            $totalEarnings += floor($usdTotal / $payment->exchange_rate);
                        }
                    } else {
                        if ($payment->exchange_rate == 0) {
                            if ($this->updateExchangeRates()) {
                                $totalEarnings += floor($payment->total / $payment->exchange_rate);
                            }
                        } else {
                            $totalEarnings += floor($payment->total / $payment->exchange_rate);
                        }
                    }
                } else {
                    $totalEarnings += round($payment->total, 2);
                }
            }
            $this->totalEarnings = $totalEarnings;

            $this->totalProfit = $this->totalEarnings - $this->totalExpenses;

            // Total Pending amount
            $invoices = Invoice::whereBetween(DB::raw('DATE(invoices.`created_at`)'), [$this->fromDate, $this->toDate])
                ->join('currencies', 'currencies.id', '=', 'invoices.currency_id')
                ->where('invoices.status', 'unpaid')
                ->orWhere('invoices.status', 'partial')
                ->select(
                    'invoices.*',
                    'currencies.currency_code',
                    'currencies.is_cryptocurrency',
                    'currencies.usd_price',
                    'currencies.exchange_rate'
                )
                ->get();
            // dd($invoices);
            $totalPendingAmount = 0;
            foreach ($invoices as $invoice) {
                if ($invoice->currency_code != $this->global->currency->currency_code) {
                    // dd('test');
                    if ($invoice->is_cryptocurrency == 'yes') {
                        if ($invoice->exchange_rate == 0) {
                            if ($this->updateExchangeRates()) {
                                $usdTotal = ($invoice->due_amount * $invoice->usd_price);
                                $totalPendingAmount += floor($usdTotal / $invoice->exchange_rate);
                            }
                        } else {
                            $usdTotal = ($invoice->due_amount * $invoice->usd_price);
                            $totalPendingAmount += floor($usdTotal / $invoice->exchange_rate);
                        }
                    } else {
                        if ($invoice->exchange_rate == 0) {
                            if ($this->updateExchangeRates()) {
                                $totalPendingAmount += floor($invoice->due_amount / $invoice->exchange_rate);
                            }
                        } else {
                            $totalPendingAmount += floor($invoice->due_amount / $invoice->exchange_rate);
                        }
                    }
                } else {
                    $totalPendingAmount += round($invoice->due_amount, 2);
                }
                // $totalPendingAmount += $invoice->due_amount;
            }
            $this->totalPendingAmount = $totalPendingAmount;

            // earnings by client

            $projects = clone $paymentsModal;
            $projects->join('currencies', 'currencies.id', '=', 'payments.currency_id')
                ->join('projects', 'projects.id', '=', 'payments.project_id')
                ->join('users', 'users.id', '=', 'projects.client_id')
                ->where('payments.status', 'complete')
                ->groupBy('date')
                ->orderBy('payments.paid_on', 'ASC')
                ->select(
                    DB::raw('DATE_FORMAT(payments.paid_on,"%Y-%m-%d") as date'),
                    DB::raw('sum(payments.amount) as total'),
                    'currencies.currency_code',
                    'currencies.is_cryptocurrency',
                    'currencies.usd_price',
                    'currencies.exchange_rate',
                    'users.name'
                );

            $invoices = clone $paymentsModal;
            $invoices = $invoices->join('currencies', 'currencies.id', '=', 'payments.currency_id')
                ->join('invoices', 'invoices.id', '=', 'payments.invoice_id')
                ->join('users', 'users.id', '=', 'invoices.client_id')
                ->where('payments.status', 'complete')
                ->groupBy('date')
                ->orderBy('payments.paid_on', 'ASC')
                ->select(
                    DB::raw('DATE_FORMAT(payments.paid_on,"%Y-%m-%d") as date'),
                    DB::raw('sum(payments.amount) as total'),
                    'currencies.currency_code',
                    'currencies.is_cryptocurrency',
                    'currencies.usd_price',
                    'currencies.exchange_rate',
                    'users.name'
                )
                ->union($projects)
                ->get();

            $chartData = array();
            $chartDataClients = array();
            foreach ($invoices as $chart) {
                if (!array_key_exists($chart->name, $chartDataClients)) {
                    $chartDataClients[$chart->name] = 0;
                }
                if ($chart->currency_code != $this->global->currency->currency_code) {
                    if ($chart->is_cryptocurrency == 'yes') {
                        if ($chart->exchange_rate == 0) {
                            if ($this->updateExchangeRates()) {
                                $usdTotal = ($chart->total * $chart->usd_price);
                                $chartDataClients[$chart->name] = $chartDataClients[$chart->name] + floor($usdTotal / $chart->exchange_rate);
                            }
                        } else {
                            $usdTotal = ($chart->total * $chart->usd_price);
                            $chartDataClients[$chart->name] = $chartDataClients[$chart->name] + floor($usdTotal / $chart->exchange_rate);
                        }
                    } else {
                        if ($chart->exchange_rate == 0) {
                            if ($this->updateExchangeRates()) {
                                $chartDataClients[$chart->name] = $chartDataClients[$chart->name] + floor($chart->total / $chart->exchange_rate);
                            }
                        } else {
                            $chartDataClients[$chart->name] = $chartDataClients[$chart->name] + floor($chart->total / $chart->exchange_rate);
                        }
                    }
                } else {
                    $chartDataClients[$chart->name] = $chartDataClients[$chart->name] + round($chart->total, 2);
                }
            }
            foreach ($chartDataClients as $key => $chartDataClient) {
                $chartData[] = ['client' => $key, 'total' => $chartDataClient];
            }

            $this->earningsByClient = json_encode($chartData);

            // earnings By Projects
            $projects = clone $paymentsModal;
            $projects->join('currencies', 'currencies.id', '=', 'payments.currency_id')
                ->join('projects', 'projects.id', '=', 'payments.project_id')
                ->where('payments.status', 'complete')
                ->orderBy('payments.paid_on', 'ASC')
                ->select(
                    DB::raw('sum(payments.amount) as total'),
                    'currencies.currency_code',
                    'currencies.is_cryptocurrency',
                    'currencies.usd_price',
                    'currencies.exchange_rate',
                    'projects.project_name'
                );

            $invoices = clone $paymentsModal;
            $invoices = $invoices->join('currencies', 'currencies.id', '=', 'payments.currency_id')
                ->join('invoices', 'invoices.id', '=', 'payments.invoice_id')
                ->join('projects', 'projects.id', '=', 'invoices.project_id')
                ->where('payments.status', 'complete')
                ->orderBy('payments.paid_on', 'ASC')
                ->select(
                    DB::raw('sum(payments.amount) as total'),
                    'currencies.currency_code',
                    'currencies.is_cryptocurrency',
                    'currencies.usd_price',
                    'currencies.exchange_rate',
                    'projects.project_name'
                )
                ->union($projects)->get();

            $projectChartData = array();
            $earningsByProjects = array();
            foreach ($invoices as $invoice) {
                if (!array_key_exists($invoice->project_name, $earningsByProjects)) {
                    $earningsByProjects[$invoice->project_name] = 0;
                }
                if ($invoice->currency_code != $this->global->currency->currency_code) {
                    if ($invoice->is_cryptocurrency == 'yes') {
                        if ($invoice->exchange_rate == 0) {
                            if ($this->updateExchangeRates()) {
                                $usdTotal = ($invoice->total * $invoice->usd_price);
                                $earningsByProjects[$invoice->project_name] = $earningsByProjects[$invoice->project_name] + floor($usdTotal / $invoice->exchange_rate);
                            }
                        } else {
                            $usdTotal = ($invoice->total * $invoice->usd_price);
                            $earningsByProjects[$invoice->project_name] = $earningsByProjects[$invoice->project_name] + floor($usdTotal / $invoice->exchange_rate);
                        }
                    } else {
                        if ($invoice->exchange_rate == 0) {
                            if ($this->updateExchangeRates()) {
                                $earningsByProjects[$invoice->project_name] = $earningsByProjects[$invoice->project_name] + floor($invoice->total / $invoice->exchange_rate);
                            }
                        } else {
                            $earningsByProjects[$invoice->project_name] = $earningsByProjects[$invoice->project_name] + floor($invoice->total / $invoice->exchange_rate);
                        }
                    }
                } else {
                    $earningsByProjects[$invoice->project_name] = $earningsByProjects[$invoice->project_name] + round($invoice->total, 2);
                }
            }
            foreach ($earningsByProjects as $key => $earningsByProject) {
                $projectChartData[] = ['project' => $key, 'total' => $earningsByProject];
            }
            $this->earningsByProjects = json_encode($projectChartData);

            // Invoice overview
            $invoiceOverviews = array();

            $allInvoice = Invoice::whereBetween(DB::raw('DATE(`issue_date`)'), [$this->fromDate, $this->toDate])->get();
            // dd($this->toDate);
            $allInvoiceCount = $allInvoice->count();

            $invoiceOverviews['invoiceDraft']['count'] = $allInvoice->filter(function ($value, $key) {
                return $value->status == 'draft';
            })->count();
            $invoiceOverviews['invoiceDraft']['color'] = 'blue';
            $invoiceOverviews['invoiceDraft']['percent'] = $this->getPercentage($allInvoiceCount, $invoiceOverviews['invoiceDraft']['count']);

            $invoiceOverviews['invoiceNotSent']['count'] = $allInvoice->filter(function ($value, $key) {
                return $value->send_status == 0;
            })->count();
            $invoiceOverviews['invoiceNotSent']['color'] = 'gray';
            $invoiceOverviews['invoiceNotSent']['percent'] = $this->getPercentage($allInvoiceCount, $invoiceOverviews['invoiceNotSent']['count']);

            $invoiceOverviews['invoiceUnpaid']['count'] = $allInvoice->filter(function ($value, $key) {
                return $value->status == 'unpaid';
            })->count();
            $invoiceOverviews['invoiceUnpaid']['color'] = 'red';
            $invoiceOverviews['invoiceUnpaid']['percent'] = $this->getPercentage($allInvoiceCount, $invoiceOverviews['invoiceUnpaid']['count']);

            $invoiceOverviews['invoiceOverdue']['count'] = $allInvoice->filter(function ($value, $key) {
                return ($value->status == 'unpaid' || $value->status == 'partial') && $value->due_date->lessThan(Carbon::now());
            })->count();
            $invoiceOverviews['invoiceOverdue']['color'] = 'orange';
            $invoiceOverviews['invoiceOverdue']['percent'] = $this->getPercentage($allInvoiceCount, $invoiceOverviews['invoiceOverdue']['count']);

            $invoiceOverviews['invoicePartiallyPaid']['count'] = $allInvoice->filter(function ($value, $key) {
                return $value->status == 'partial';
            })->count();
            $invoiceOverviews['invoicePartiallyPaid']['color'] = 'yellow';
            $invoiceOverviews['invoicePartiallyPaid']['percent'] = $this->getPercentage($allInvoiceCount, $invoiceOverviews['invoicePartiallyPaid']['count']);

            $invoiceOverviews['invoicePaid']['count'] = $allInvoice->filter(function ($value, $key) {
                return $value->status == 'paid';
            })->count();
            $invoiceOverviews['invoicePaid']['color'] = 'green';
            $invoiceOverviews['invoicePaid']['percent'] = $this->getPercentage($allInvoiceCount, $invoiceOverviews['invoicePaid']['count']);

            $this->invoiceOverviews = $invoiceOverviews;
            $this->invoiceOverviewCount = $allInvoiceCount;

            // Estimate overview
            $estimateOverviews = array();

            $allEstimate = Estimate::whereBetween(DB::raw('DATE(`valid_till`)'), [$this->fromDate, $this->toDate])->get();
            $allEstimateCount = $allEstimate->count();

            $estimateOverviews['estimateDraft']['count'] = $allEstimate->filter(function ($value, $key) {
                return $value->status == 'draft';
            })->count();
            $estimateOverviews['estimateDraft']['color'] = 'blue';
            $estimateOverviews['estimateDraft']['percent'] = $this->getPercentage($allEstimateCount, $estimateOverviews['estimateDraft']['count']);

            $estimateOverviews['estimateNotSent']['count'] = $allEstimate->filter(function ($value, $key) {
                return $value->send_status == 0;
            })->count();
            $estimateOverviews['estimateNotSent']['color'] = 'gray';
            $estimateOverviews['estimateNotSent']['percent'] = $this->getPercentage($allEstimateCount, $estimateOverviews['estimateNotSent']['count']);

            $estimateOverviews['estimateSent']['count'] = $allEstimate->filter(function ($value, $key) {
                return $value->send_status == 1;
            })->count();
            $estimateOverviews['estimateSent']['color'] = 'yellow';
            $estimateOverviews['estimateSent']['percent'] = $this->getPercentage($allEstimateCount, $estimateOverviews['estimateSent']['count']);

            $estimateOverviews['estimateDeclined']['count'] = $allEstimate->filter(function ($value, $key) {
                return $value->status == 'declined';
            })->count();
            $estimateOverviews['estimateDeclined']['color'] = 'orange';
            $estimateOverviews['estimateDeclined']['percent'] = $this->getPercentage($allEstimateCount, $estimateOverviews['estimateDeclined']['count']);

            $estimateOverviews['estimateExpired']['count'] = $allEstimate->filter(function ($value, $key) {
                return ($value->status != 'sent' || $value->status == 'waiting') && $value->valid_till->lessThan(Carbon::now());
            })->count();
            $estimateOverviews['estimateExpired']['color'] = 'red';
            $estimateOverviews['estimateExpired']['percent'] = $this->getPercentage($allEstimateCount, $estimateOverviews['estimateExpired']['count']);

            $estimateOverviews['estimateAccepted']['count'] = $allEstimate->filter(function ($value, $key) {
                return $value->status == 'accepted';
            })->count();
            $estimateOverviews['estimateAccepted']['color'] = 'green';
            $estimateOverviews['estimateAccepted']['percent'] = $this->getPercentage($allEstimateCount, $estimateOverviews['estimateAccepted']['count']);

            $this->estimateOverviews = $estimateOverviews;
            $this->estimateOverviewCount = $allEstimateCount;


            // Proposal overview

            $proposalOverviews = array();

            $allProposal = Proposal::whereBetween(DB::raw('DATE(`created_at`)'), [$this->fromDate, $this->toDate])->get();
            $allProposalCount = $allProposal->count();

            $proposalOverviews['proposalWaiting']['count'] = $allProposal->filter(function ($value, $key) {
                return $value->status == 'waiting';
            })->count();
            $proposalOverviews['proposalWaiting']['color'] = 'blue';
            $proposalOverviews['proposalWaiting']['percent'] = $this->getPercentage($allProposalCount, $proposalOverviews['proposalWaiting']['count']);

            $proposalOverviews['proposalDeclined']['count'] = $allProposal->filter(function ($value, $key) {
                return $value->status == 'declined';
            })->count();
            $proposalOverviews['proposalDeclined']['color'] = 'orange';
            $proposalOverviews['proposalDeclined']['percent'] = $this->getPercentage($allProposalCount, $proposalOverviews['proposalDeclined']['count']);

            $proposalOverviews['proposalExpired']['count'] = $allProposal->filter(function ($value, $key) {
                return $value->status != 'declined' && $value->valid_till->lessThan(Carbon::now());
            })->count();
            $proposalOverviews['proposalExpired']['color'] = 'red';
            $proposalOverviews['proposalExpired']['percent'] = $this->getPercentage($allProposalCount, $proposalOverviews['proposalExpired']['count']);

            $proposalOverviews['proposalAccepted']['count'] = $allProposal->filter(function ($value, $key) {
                return $value->status == 'accepted';
            })->count();
            $proposalOverviews['proposalAccepted']['color'] = 'yellow';
            $proposalOverviews['proposalAccepted']['percent'] = $this->getPercentage($allProposalCount, $proposalOverviews['proposalAccepted']['count']);

            $proposalOverviews['proposalConverted']['count'] = $allProposal->filter(function ($value, $key) {
                return $value->invoice_convert == 1;
            })->count();
            $proposalOverviews['proposalConverted']['color'] = 'green';
            $proposalOverviews['proposalConverted']['percent'] = $this->getPercentage($allProposalCount, $proposalOverviews['proposalConverted']['count']);

            $this->proposalOverviews = $proposalOverviews;
            $this->proposalOverviewCount = $allProposalCount;


            $view = view('admin.dashboard.finance-dashboard', $this->data)->render();
            return Reply::dataOnly(['view' => $view]);
        }

        return view('admin.dashboard.finance', $this->data);
    }

    public function getPercentage($total, $count)
    {
        $percentage = 0;
        try {
            $percentage = number_format(($count * 100) / $total, 2);
            return $percentage;
        } catch (Exception $e) {
            return 0;
        }
        // return $percentage;
    }

    public function financeDashboardInvoice(InvoicesDataTable $dataTable)
    {
        return $dataTable->render('admin.dashboard.finance', $this->data);
    }

    public function financeDashboardEstimate(EstimatesDataTable $dataTable)
    {
        return $dataTable->render('admin.dashboard.finance', $this->data);
    }

    public function financeDashboardExpense(ExpensesDataTable $dataTable)
    {
        return $dataTable->render('admin.dashboard.finance', $this->data);
    }

    public function financeDashboardPayment(PaymentsDataTable $dataTable)
    {
        return $dataTable->render('admin.dashboard.finance', $this->data);
    }

    public function financeDashboardProposal(ProposalDataTable $dataTable)
    {
        return $dataTable->render('admin.dashboard.finance', $this->data);
    }
    // finance Dashboard end

    // HR Dashboard start

    public function hrDashboard(Request $request)
    {

        $this->pageTitle = 'app.hrDashboard';
        $this->fromDate = Carbon::now()->timezone($this->global->timezone)->subDays(30)->toDateString();
        $this->toDate = Carbon::now()->timezone($this->global->timezone)->toDateString();

        $this->widgets = DashboardWidget::where('dashboard_type', 'admin-hr-dashboard')->get();
        $this->activeWidgets = DashboardWidget::where('dashboard_type', 'admin-hr-dashboard')->where('status', 1)->get()->pluck('widget_name')->toArray();

        if (request()->ajax()) {
            if (!is_null($request->startDate) && $request->startDate != "null" && !is_null($request->endDate) && $request->endDate != "null") {
                $this->fromDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
                $this->toDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            }

            $this->totalLeavesApproved = Leave::whereBetween(DB::raw('DATE(`updated_at`)'), [$this->fromDate, $this->toDate])->where('status', 'approved')->get()->count();
            $this->totalNewEmployee = EmployeeDetails::whereBetween(DB::raw('DATE(`joining_date`)'), [$this->fromDate, $this->toDate])->get()->count();
            $this->totalEmployeeExits = EmployeeDetails::whereBetween(DB::raw('DATE(`last_date`)'), [$this->fromDate, $this->toDate])->get()->count();

            $this->departmentWiseEmployee = Team::join('employee_details', 'employee_details.department_id', 'teams.id')
                ->whereBetween(DB::raw('DATE(employee_details.`created_at`)'), [$this->fromDate, $this->toDate])
                ->select(DB::raw('count(employee_details.id) as totalEmployee'), 'teams.team_name')
                ->groupBy('teams.team_name')
                ->get()->toJson();

            $this->designationWiseEmployee = Designation::join('employee_details', 'employee_details.designation_id', 'designations.id')
                ->whereBetween(DB::raw('DATE(employee_details.`created_at`)'), [$this->fromDate, $this->toDate])
                ->select(DB::raw('count(employee_details.id) as totalEmployee'), 'designations.name')
                ->groupBy('designations.name')
                ->get()->toJson();

            $this->genderWiseEmployee = EmployeeDetails::whereBetween(DB::raw('DATE(employee_details.`created_at`)'), [$this->fromDate, $this->toDate])
                ->join('users', 'users.id', 'employee_details.user_id')
                ->select(DB::raw('count(employee_details.id) as totalEmployee'), 'users.gender')
                ->groupBy('users.gender')
                ->orderBy('users.gender', 'ASC')
                ->get()->toJson();

            $this->roleWiseEmployee = EmployeeDetails::whereBetween(DB::raw('DATE(employee_details.`created_at`)'), [$this->fromDate, $this->toDate])
                ->Join('users', 'users.id', 'employee_details.user_id')
                ->join('role_user', 'role_user.user_id', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'role_user.role_id')
                ->where('roles.name', '<>', 'client')
                ->select(DB::raw('count(employee_details.id) as totalEmployee'), 'roles.name')
                ->groupBy('roles.name')
                ->orderBy('roles.name', 'ASC')
                ->get()->toJson();

            $attandance = EmployeeDetails::join('users', 'users.id', 'employee_details.user_id')
                ->join('attendances', 'attendances.user_id', 'users.id')
                ->whereBetween(DB::raw('DATE(attendances.`clock_in_time`)'), [$this->fromDate, $this->toDate])
                ->select(DB::raw('count(users.id) as employeeCount'), DB::raw('DATE(attendances.clock_in_time) as date'))
                ->groupBy('date')
                ->get();
            try {
                $this->averageAttendance = number_format(((array_sum(array_column($attandance->toArray(), 'employeeCount')) / $attandance->count()) * 100) / User::allEmployees()->count(), 2) . '%';
            } catch (Exception $e) {
                $this->averageAttendance = '0%';
            }

            $this->leavesTakens = EmployeeDetails::join('users', 'users.id', 'employee_details.user_id')
                ->join('leaves', 'leaves.user_id', 'users.id')
                ->whereBetween(DB::raw('DATE(leaves.`leave_date`)'), [$this->fromDate, $this->toDate])
                ->where('leaves.status', 'approved')
                ->select(DB::raw('count(leaves.id) as employeeLeaveCount'), 'users.name', 'users.id', 'users.image')
                ->groupBy('users.id')
                ->orderBy('employeeLeaveCount', 'DESC')
                ->get();

            $this->lateAttendanceMarks = EmployeeDetails::join('users', 'users.id', 'employee_details.user_id')
                ->join('attendances', 'attendances.user_id', 'users.id')
                ->whereBetween(DB::raw('DATE(attendances.`clock_in_time`)'), [$this->fromDate, $this->toDate])
                ->where('late', 'yes')
                ->select(DB::raw('count(attendances.id) as employeeLateCount'), 'users.id', 'users.name', 'users.image')
                ->groupBy('users.id')
                ->orderBy('employeeLateCount', 'DESC')
                ->get();

            // dd($lateMarksCount);

            $view = view('admin.dashboard.hr-dashboard', $this->data)->render();
            return Reply::dataOnly(['view' => $view]);
        }

        return view('admin.dashboard.hr', $this->data);
    }

    // HR Dashboard end

    // Project Dashboard start

    public function projectDashboard(Request $request)
    {

        $this->pageTitle = 'app.projectDashboard';
        $this->fromDate = Carbon::now()->timezone($this->global->timezone)->subDays(30)->toDateString();
        $this->toDate = Carbon::now()->timezone($this->global->timezone)->toDateString();

        $this->widgets = DashboardWidget::where('dashboard_type', 'admin-project-dashboard')->get();
        $this->activeWidgets = DashboardWidget::where('dashboard_type', 'admin-project-dashboard')->where('status', 1)->get()->pluck('widget_name')->toArray();

        if (request()->ajax()) {
            if (!is_null($request->startDate) && $request->startDate != "null" && !is_null($request->endDate) && $request->endDate != "null") {
                $this->fromDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
                $this->toDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            }

            $this->totalProject = Project::whereBetween(DB::raw('DATE(`created_at`)'), [$this->fromDate, $this->toDate])
                ->get()->count();

            $hoursLogged = ProjectTimeLog::whereBetween(DB::raw('DATE(`created_at`)'), [$this->fromDate, $this->toDate])
                ->select(DB::raw('(select sum(project_time_logs.total_minutes) from `project_time_logs`) as totalHoursLogged'))
                ->get()->count();

            $timeLog = intdiv($hoursLogged, 60) . ' ' . __('app.hrs') . ' ';
            if (($hoursLogged % 60) > 0) {
                $timeLog .= ($hoursLogged % 60) . ' ' . __('app.mins');
            }

            $this->totalHoursLogged = $timeLog;

            $this->totalOverdueProject = Project::whereBetween(DB::raw('DATE(`created_at`)'), [$this->fromDate, $this->toDate])
                ->whereNotNull('deadline')
                ->where(DB::raw('DATE(deadline)'), '<=', Carbon::now()->timezone($this->global->timezone)->format('Y-m-d'))
                ->get()->count();

            $this->statusWiseProject = Project::whereBetween(DB::raw('DATE(`created_at`)'), [$this->fromDate, $this->toDate])
                ->select(DB::raw('count(id) as totalProject'), 'status')
                ->groupBy('status')
                ->get()->toJson();
            // dd($this->statusWiseProject);

            $this->pendingMilestone = ProjectMilestone::whereBetween(DB::raw('DATE(project_milestones.`created_at`)'), [$this->fromDate, $this->toDate])
                ->join('projects', 'projects.id', 'project_milestones.project_id')
                ->join('currencies', 'currencies.id', '=', 'projects.currency_id')
                ->where('project_milestones.status', 'incomplete')
                ->select('project_milestones.milestone_title', 'project_milestones.project_id', 'project_milestones.cost', 'projects.project_name', 'currencies.currency_symbol')
                ->get();

            // dd($this->pendingMilestone);

            $view = view('admin.dashboard.project-dashboard', $this->data)->render();
            return Reply::dataOnly(['view' => $view]);
        }

        return view('admin.dashboard.project', $this->data);
    }

    // Project Dashboard end

    // Ticket Dashboard start

    public function ticketDashboard(Request $request)
    {

        $this->pageTitle = 'app.ticketDashboard';
        $this->fromDate = Carbon::now()->timezone($this->global->timezone)->subDays(30)->toDateString();
        $this->toDate = Carbon::now()->timezone($this->global->timezone)->toDateString();

        $this->widgets = DashboardWidget::where('dashboard_type', 'admin-ticket-dashboard')->get();
        $this->activeWidgets = DashboardWidget::where('dashboard_type', 'admin-ticket-dashboard')->where('status', 1)->get()->pluck('widget_name')->toArray();

        if (request()->ajax()) {
            if (!is_null($request->startDate) && $request->startDate != "null" && !is_null($request->endDate) && $request->endDate != "null") {
                $this->fromDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
                $this->toDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            }

            $this->totalUnresolvedTickets = Ticket::whereBetween(DB::raw('DATE(`created_at`)'), [$this->fromDate, $this->toDate])
                ->where('status', '!=', 'resolved')
                ->get()->count();

            $this->totalUnassignedTicket = Ticket::whereBetween(DB::raw('DATE(`created_at`)'), [$this->fromDate, $this->toDate])
                ->whereNull('agent_id')
                ->get()->count();

            $this->statusWiseTicket = Ticket::whereBetween(DB::raw('DATE(`created_at`)'), [$this->fromDate, $this->toDate])
                ->select(DB::raw('count(id) as totalTicket'), 'status')
                ->groupBy('status')
                ->get()->toJson();

            $this->typeWiseTicket = Ticket::whereBetween(DB::raw('DATE(tickets.`created_at`)'), [$this->fromDate, $this->toDate])
                ->join('ticket_types', 'ticket_types.id', 'tickets.type_id')
                ->select(DB::raw('count(tickets.id) as totalTicket'), 'ticket_types.type')
                ->groupBy('ticket_types.type')
                ->get()->toJson();

            $this->channelWiseTicket = Ticket::whereBetween(DB::raw('DATE(tickets.`created_at`)'), [$this->fromDate, $this->toDate])
                ->join('ticket_channels', 'ticket_channels.id', 'tickets.channel_id')
                ->select(DB::raw('count(tickets.id) as totalTicket'), 'ticket_channels.channel_name')
                ->groupBy('ticket_channels.channel_name')
                ->get()->toJson();

            $this->newTickets = Ticket::whereBetween(DB::raw('DATE(`created_at`)'), [$this->fromDate, $this->toDate])
                ->where('status', 'open')
                ->orderBy('id', 'desc')->get();

            $view = view('admin.dashboard.ticket-dashboard', $this->data)->render();
            return Reply::dataOnly(['view' => $view]);
        }

        return view('admin.dashboard.ticket', $this->data);
    }

    // Ticket Dashboard end

}

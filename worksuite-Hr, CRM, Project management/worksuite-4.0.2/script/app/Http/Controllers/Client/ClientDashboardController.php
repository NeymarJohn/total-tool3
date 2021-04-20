<?php

namespace App\Http\Controllers\Client;

use App\Issue;
use App\ModuleSetting;
use App\ProjectActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ClientDashboardController extends ClientBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = "app.menu.dashboard";
        $this->pageIcon = 'icon-speedometer';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->counts = DB::table('users')
            ->select(
                DB::raw('(select count(projects.id) from `projects` where client_id = ' . $this->user->id . ') as totalProjects'),
                //                DB::raw('(select count(issues.id) from `issues` where status="pending" and user_id = '.$this->user->id.') as totalPendingIssues'),
                DB::raw('(select count(tickets.id) from `tickets` where (status="open" or status="pending") and user_id = ' . $this->user->id . ') as totalUnResolvedTickets'),
                DB::raw('(select IFNULL(sum(invoices.total),0) from `invoices` inner join projects on projects.id = invoices.project_id where invoices.status="paid" and projects.client_id = ' . $this->user->id . ') as totalPaidAmount'),
                DB::raw('(select IFNULL(sum(invoices.total),0) from `invoices` inner join projects on projects.id = invoices.project_id where invoices.status="unpaid" and projects.client_id = ' . $this->user->id . ') as totalUnpaidAmount')
            )
            ->first();

        $this->pendingIssues = Issue::where('status', 'pending')->where('user_id', $this->user->id)->get();

        $this->projectActivities = ProjectActivity::join('projects', 'projects.id', '=', 'project_activity.project_id')
            ->where('projects.client_id', '=', $this->user->id)
            ->whereNull('projects.deleted_at')
            ->select('projects.project_name', 'project_activity.created_at', 'project_activity.activity', 'project_activity.project_id')
            ->limit(15)
            ->orderBy('project_activity.id', 'desc')
            ->get();

        return view('client.dashboard.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

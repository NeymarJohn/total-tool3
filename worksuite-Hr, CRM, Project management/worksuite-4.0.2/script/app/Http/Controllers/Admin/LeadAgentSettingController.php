<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\LeadSetting\StoreLeadAgent;
use App\LeadAgent;
use App\User;

class LeadAgentSettingController extends AdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'modules.lead.leadAgent';
        $this->pageIcon = 'ti-settings';
        $this->middleware(function ($request, $next) {
            if(!in_array('leads',$this->modules)){
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
    public function index()
    {
        $this->leadAgents = LeadAgent::with('user')->get();
        $this->employees = User::doesntHave('lead_agent')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', 'employee')
            ->get();
        return view('admin.lead-settings.agent.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->employeeData = User::doesntHave('lead_agent')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', 'employee')
            ->get();
        $this->agentData = LeadAgent::select('lead_agents.id', 'lead_agents.user_id', 'users.name')
            ->join('users','users.id', 'lead_agents.user_id')
            ->where('users.status', 'active')
            ->get();

        return view('admin.lead.create-agent', $this->data);
    }

    /**
     * @param StoreLeadAgent $request
     * @return array
     */
    public function storeAgent(\App\Http\Requests\Lead\StoreLeadAgent $request)
    {
        $agent = new LeadAgent();
        $agent->user_id = $request->agent_name;
        $agent->save();
        $agentData = LeadAgent::select('lead_agents.id', 'lead_agents.user_id', 'users.name')
            ->join('users','users.id', 'lead_agents.user_id')
            ->get();
        return Reply::successWithData(__('messages.leadAgentAddSuccess'),['data' => $agentData]);
    }

    /**
     * @param StoreLeadAgent $request
     * @return array
     */
    public function store(StoreLeadAgent $request)
    {
        $users = $request->user_id;

        foreach($users as $user) {
            $agent = new LeadAgent();
            $agent->user_id = $user;
            $agent->save();
        }

        $allAgents = LeadAgent::all();

        $select = '';
        foreach($allAgents as $sts){
            $select.= '<option value="'.$sts->id.'">'.ucwords($sts->type).'</option>';
        }

        return Reply::successWithData(__('messages.leadStatusAddSuccess'), ['optionData' => $select]);
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
     * @param $id
     */
    public function update($id)
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
        LeadAgent::destroy($id);
        $agentData = LeadAgent::select('lead_agents.id', 'lead_agents.user_id', 'users.name')
            ->join('users','users.id', 'lead_agents.user_id')
            ->get();
        $employeeData = User::doesntHave('lead_agent')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', 'employee')
            ->get();

        $empDatas = [];
        foreach($employeeData as $empData){
            $empDatas[] = ['name' => $empData->name,'email' => $empData->email,'id' => $empData->id,'created_at' => $empData->created_at, ];
        }
        return Reply::successWithData(__('messages.leadStatusDeleteSuccess'),['data' => $agentData,'empData' => $empDatas]);
    }

    public function createModal(){
//        return view('admin.lead-settings.status.create-modal');
    }
}

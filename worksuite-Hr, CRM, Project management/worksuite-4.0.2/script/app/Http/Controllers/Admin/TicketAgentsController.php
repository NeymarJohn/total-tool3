<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\TicketAgentGroups\StoreAgentGroup;
use App\TicketAgentGroups;
use App\TicketGroup;
use App\User;
use Illuminate\Http\Request;

class TicketAgentsController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.ticketAgents';
        $this->pageIcon = 'ti-headphone-alt';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->agents = TicketAgentGroups::with('user')->get();
        $this->employees = User::doesntHave('agent')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', 'employee')
            ->get();
        $this->groups = TicketGroup::all();

        return view('admin.ticket-settings.agents.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->employees = User::doesntHave('agent')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', 'employee')
            ->get();
        $this->groups = TicketGroup::all();
        return view('admin.ticket-settings.agents.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAgentGroup $request)
    {
        $users = $request->user_id;

        foreach ($users as $user) {
            $agent = new TicketAgentGroups();
            $agent->agent_id = $user;
            $agent->group_id = $request->group_id;
            $agent->save();
        }
        if (request()->ajax()) {
            $groups = TicketGroup::all();
            $agentList = '';
            foreach ($groups as $group) {
                if (count($group->enabled_agents) > 0) {

                    $agentList .= '<optgroup label="' . ucwords($group->group_name) . '">';
                    foreach ($group->enabled_agents as $agent) {
                        $agentList .= '<option value="' . $agent->user->id . '">' . ucwords($agent->user->name) . ' [' . $agent->user->email . ']' . '</option>';
                    }
                    $agentList .= '</optgroup>';
                }
            }

            return Reply::successWithData(__('messages.agentAddedSuccessfully'), ['teamData' => $agentList]);
        }
        return Reply::success(__('messages.agentAddedSuccessfully'));
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
        $agent = TicketAgentGroups::findOrFail($id);
        $agent->status = $request->status;
        $agent->save();

        return Reply::success(__('messages.statusUpdatedSuccessfully'));
    }

    public function updateGroup(Request $request, $id)
    {
        $agent = TicketAgentGroups::findOrFail($id);
        $agent->group_id = $request->groupId;
        $agent->save();

        return Reply::success(__('messages.groupUpdatedSuccessfully'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TicketAgentGroups::destroy($id);

        return Reply::success(__('messages.agentRemoveSuccess'));
    }
}

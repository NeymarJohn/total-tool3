<?php

namespace App\Http\Controllers\Admin;

use App\EmployeeTeam;
use App\Helper\Reply;
use App\Http\Requests\Team\StoreDepartment;
use App\Http\Requests\Team\StoreTeam;
use App\Team;
use App\User;

class ManageTeamsController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.department';
        $this->pageIcon = 'icon-user';
        $this->middleware(function ($request, $next) {
            if(!in_array('employees',$this->user->modules)){
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
        $this->groups = Team::with('member', 'member.user')->get();

        return view('admin.teams.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.teams.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTeam $request)
    {
        $group = new Team();
        $group->team_name = $request->team_name;
        $group->save();

        return Reply::redirect(route('admin.teams.index'), 'Group created successfully.');
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
        $this->group = Team::with('member')->findOrFail($id);

        return view('admin.teams.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTeam $request, $id)
    {
        $group = Team::find($id);
        $group->team_name = $request->team_name;
        $group->save();

        if(!empty($users = $request->user_id)){
            foreach($users as $user){
                $member = new EmployeeTeam();
                $member->user_id = $user;
                $member->team_id = $id;
                $member->save();
            }
        }


        return Reply::redirect(route('admin.teams.index'), __('messages.groupUpdatedSuccessfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Team::destroy($id);
        return Reply::successWithData(__('messages.deleteSuccess'), ['status' => 'success']);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function quickCreate()
    {
        $this->teams = Team::all();
        return view('admin.teams.quick-create', $this->data);
    }
    /**
     * @param StoreDepartment $request
     * @return array
     */
    public function quickStore(StoreDepartment $request)
    {
        $group = new Team();
        $group->team_name = $request->department_name;
        $group->save();

        $teams = Team::all();
        $teamData = '';

        foreach ($teams as $team) {
            $selected = '';

            if ($team->id == $group->id) {
                $selected = 'selected';
            }

            $teamData .= '<option ' . $selected . ' value="' . $team->id . '"> ' . $team->team_name . ' </option>';
        }

        return Reply::successWithData(__('messages.departmentAdded'), ['teamData' => $teamData]);
    }

}

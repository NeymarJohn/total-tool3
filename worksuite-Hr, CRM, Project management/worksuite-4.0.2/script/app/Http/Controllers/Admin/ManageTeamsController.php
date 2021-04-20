<?php

namespace App\Http\Controllers\Admin;

use App\EmployeeDetails;
use App\Helper\Reply;
use App\Http\Requests\Team\StoreDepartment;
use App\Http\Requests\Team\StoreRequest;
use App\Http\Requests\Team\StoreTeam;
use App\Team;
use App\User;

class ManageTeamsController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.teams';
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
        $this->groups = Team::with('team_members', 'team_members.user')->get();
        return view('admin.department.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.department.create', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function quickCreate()
    {
        $this->teams = Team::all();
        return view('admin.department.quick-create', $this->data);
    }

    /**
     * @param StoreTeam $request
     * @return array
     */
    public function store(StoreTeam $request)
    {
        $group = new Team();
        $group->team_name = $request->team_name;
        $group->save();

        return Reply::redirect(route('admin.department.index'), __('messages.departmentAdded'));
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

    /**
     * Display the specified resource.
     *[
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
        $this->group = Team::with('team_members', 'team_members.user')->findOrFail($id);

        $this->employees = EmployeeDetails::with('user')->where('department_id', '<>', $id)->get();

        return view('admin.department.edit', $this->data);
    }

    /**
     * @param StoreTeam $request
     * @param $id
     * @return array
     */
    public function update(StoreTeam $request, $id)
    {
        $group = Team::find($id);
        $group->team_name = $request->team_name;
        $group->save();

        if ($request->user_id) {
            EmployeeDetails::whereIn('id', $request->user_id)->update(
                [
                    'department_id' => $id
                ]
            );
        }
        

        return Reply::redirect(route('admin.department.index'), __('messages.departmentUpdated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        EmployeeDetails::where('department_id', $id)->update(['department_id' => NULL]);
        Team::destroy($id);
        return Reply::dataOnly(['status' => 'success']);
    }
}

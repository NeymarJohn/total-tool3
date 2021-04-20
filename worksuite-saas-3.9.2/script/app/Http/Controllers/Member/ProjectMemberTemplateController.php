<?php

namespace App\Http\Controllers\Member;

use App\EmployeeDetails;
use App\EmployeeTeam;
use App\Helper\Reply;
use App\Http\Requests\ProjectMembers\SaveGroupMembers;
use App\Http\Requests\ProjectMembers\StoreProjectMembers;
use App\ProjectTemplate;
use App\ProjectTemplateMember;
use App\Team;
use App\User;
use Illuminate\Http\Request;

class ProjectMemberTemplateController extends MemberBaseController
{

    public function __construct() {
        parent::__construct();
        $this->pageIcon = 'icon-layers';
        $this->pageTitle = 'app.menu.projectTemplateMember';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(StoreProjectMembers $request)
    {
        $users = $request->user_id;

        foreach($users as $user){
            $member = new ProjectTemplateMember();
            $member->user_id = $user;
            $member->project_template_id = $request->project_id;
            $member->save();
        }

        return Reply::success(__('messages.templateMembersAddedSuccessfully'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->project = ProjectTemplate::findOrFail($id);
        $this->employees = User::doesntHave('member', 'and', function($query) use ($id){
            $query->where('project_id', $id);
        })
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->where('roles.name', 'employee')
            ->get();
        $this->groups = Team::all();

        return view('member.project-template.project-member.create', $this->data);
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
        $projectMember = ProjectTemplateMember::findOrFail($id);

        $projectMember->delete();

        return Reply::success(__('messages.templateMemberRemovedFromProject'));
    }

    public function storeGroup(SaveGroupMembers $request)
    {
        $groups = $request->group_id;
        $project = ProjectTemplate::find($request->project_id);

        foreach($groups as $group){

            $members = EmployeeDetails::where('department_id', $group)->get();

            foreach ($members as $user){
                $check = ProjectTemplateMember::where('user_id', $user->user_id)->where('project_template_id', $request->project_id)->first();

                if(is_null($check)){
                    $member = new ProjectTemplateMember();
                    $member->user_id = $user->user_id;
                    $member->project_template_id = $request->project_id;
                    $member->save();
                }
            }
        }

        return Reply::success(__('messages.membersAddedSuccessfully'));
    }
}

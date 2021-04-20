<?php

namespace App\Http\Controllers\Member;

use App\Helper\Reply;
use App\Http\Requests\ProjectTemplate\StoreProject;
use App\ProjectCategory;
use App\ProjectTemplate;
use App\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProjectTemplateController extends MemberBaseController
{

    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.projectTemplate';
        $this->pageIcon = 'icon-layers';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        return view('member.project-template.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $this->clients = User::allClients();
        $this->categories = ProjectCategory::all();
        return view('member.project-template.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProject $request) {
        $project = new ProjectTemplate();
        $project->project_name = $request->project_name;

        if ($request->project_summary != '') {
            $project->project_summary = $request->project_summary;
        }

        if ($request->notes != '') {
            $project->notes = $request->notes;
        }
        if ($request->category_id != '') {
            $project->category_id = $request->category_id;
        }

        if($request->client_view_task){
            $project->client_view_task = 'enable';
        }
        else{
            $project->client_view_task = "disable";
        }
        if(($request->client_view_task) && ($request->client_task_notification)){
            $project->allow_client_notification = 'enable';
        }
        else{
            $project->allow_client_notification = "disable";
        }

        if($request->manual_timelog){
            $project->manual_timelog = 'enable';
        }
        else{
            $project->manual_timelog = "disable";
        }
        $project->client_id = $request->client_id;

        $project->save();

        return Reply::redirect(route('member.project-template.index'), __('modules.projectTemplate.projectUpdated'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $this->project = ProjectTemplate::findOrFail($id);
        return view('member.project-template.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $this->clients = User::allClients();
        $this->categories = ProjectCategory::all();
        $this->template = ProjectTemplate::findOrFail($id);
        return view('member.project-template.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProject $request, $id) {
        $project = ProjectTemplate::findOrFail($id);
        $project->project_name = $request->project_name;
        if ($request->project_summary != '') {
            $project->project_summary = $request->project_summary;
        }

        if ($request->notes != '') {
            $project->notes = $request->notes;
        }
        if ($request->category_id != '') {
            $project->category_id = $request->category_id;
        }

        if($request->client_view_task){
            $project->client_view_task = 'enable';
        }
        else{
            $project->client_view_task = "disable";
        }
        if(($request->client_view_task) && ($request->client_task_notification)){
            $project->allow_client_notification = 'enable';
        }
        else{
            $project->allow_client_notification = "disable";
        }

        if($request->manual_timelog){
            $project->manual_timelog = 'enable';
        }
        else{
            $project->manual_timelog = "disable";
        }

        $project->client_id = $request->client_id;
        $project->feedback = $request->feedback;

        $project->save();

        return Reply::redirect(route('member.project-template.index', $id), __('messages.projectUpdated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        ProjectTemplate::destroy($id);
        return Reply::success(__('messages.projectDeleted'));
    }

    public function data(Request $request) {
        $projects = ProjectTemplate::with(['category', 'members'])
            ->select('id', 'project_name', 'category_id')

        ->with('category')->get();

        return DataTables::of($projects)
            ->addColumn('action', function ($row) {
                return '<a href="' . route('member.project-template.edit', [$row->id]) . '" class="btn btn-info btn-circle"
                      data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                      <a href="' . route('member.project-template.show', [$row->id]) . '" class="btn btn-success btn-circle"
                      data-toggle="tooltip" data-original-title="View Project Details"><i class="fa fa-search" aria-hidden="true"></i></a>

                      <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-user-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
            })
            ->addColumn('members', function ($row) {
                $members = '';

                if (count($row->members) > 0) {
                    foreach ($row->members as $member) {
                        $members .= '<img data-toggle="tooltip" data-original-title="' . ucwords($member->user->name) . '" src="' . $member->user->image_url . '"
                        alt="user" class="img-circle" width="30" height="30"> ';
                    }
                }
                else{
                    $members.= __('messages.noMemberAddedToProject');
                }
                $members.= '<br><br><a class="font-12" href="'.route('member.project-template-member.show', $row->id).'"><i class="fa fa-plus"></i> '.__('modules.projectTemplate.addMemberTitle').'</a>';
                return $members;
            })
            ->editColumn('project_name', function ($row) {
                return '<a href="' . route('member.project-template.show', $row->id) . '">' . ucfirst($row->project_name) . '</a>';
            })
            ->editColumn('category_id', function ($row) {
                return ($row->category) ? $row->category->category_name : '-';
            })

            ->rawColumns(['project_name', 'action', 'members', 'category_id'])
            ->make(true);
    }
}

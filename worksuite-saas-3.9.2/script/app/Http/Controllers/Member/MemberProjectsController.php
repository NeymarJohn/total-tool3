<?php

namespace App\Http\Controllers\Member;

use App\DataTables\Member\MemberDiscussionDataTable;
use App\Discussion;
use App\DiscussionCategory;
use App\DiscussionReply;
use App\Helper\Reply;
use App\Http\Requests\Project\StoreProject;
use App\Http\Requests\Project\UpdateProject;
use App\Pinned;
use App\Project;
use App\ProjectActivity;
use App\ProjectCategory;
use App\ProjectFile;
use App\ProjectMember;
use App\ProjectTemplate;
use App\ProjectTemplateMember;
use App\ProjectTimeLog;
use App\SubTask;
use App\Task;
use App\TaskboardColumn;
use App\TaskCategory;
use App\TaskUser;
use App\Traits\ProjectProgress;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class MemberProjectsController
 * @package App\Http\Controllers\Member
 */
class MemberProjectsController extends MemberBaseController
{
    use ProjectProgress;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.projects';
        $this->pageIcon = 'icon-layers';

        $this->middleware(function ($request, $next) {
            abort_if(!in_array('projects', $this->user->modules),403);
            return $next($request);
        });
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->clients = User::allClients();

        $allProject = Project::select('projects.status', 'projects.id', 'project_budget')
            ->join('project_members', 'project_members.project_id', '=', 'projects.id')
            ->where('project_members.user_id', '=', user()->id)
            ->groupBy('projects.id')
            ->get();

        // Total Projects
        $this->totalProjects = $allProject->count();

        // OverDue Projects
        $this->overdueProjects = $allProject->filter(function ($value, $key) {
            return $value->completion_percent <> '100' && $value->deadline <  Carbon::today()->timezone($this->global->timezone);
        })->count();

        // Completed Or Finished Projects
        $this->finishedProjects = $allProject->filter(function ($value, $key) {
            return $value->status == 'finished';
        })->count();

        // IN Process Projects
        $this->inProcessProjects = $allProject->filter(function ($value, $key) {
            return $value->status == 'in progress';
        })->count();

        // On Hold pROJECTS
        $this->onHoldProjects = $allProject->filter(function ($value, $key) {
            return $value->status == 'on hold';
        })->count();

        // Canceled Projects
        $this->canceledProjects = $allProject->filter(function ($value, $key) {
            return $value->status == 'canceled';
        })->count();

        // Not Started projects
        $this->notStartedProjects = $allProject->filter(function ($value, $key) {
            return $value->status == 'not started';
        })->count();

        //Budget Total
        $this->projectBudgetTotal = $allProject->sum('project_budget');

        return view('member.projects.index', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->project = Project::findOrFail($id)->withCustomFields();

        if (!$this->project->isProjectAdmin && !$this->user->cans('edit_projects')) {
            abort(403);
        }

        $this->clients = User::allClients();
        $this->categories = ProjectCategory::all();
        $this->fields = $this->project->getCustomFieldGroupsWithFields()->fields;

        return view('member.projects.edit', $this->data);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $this->userDetail = auth()->user();

        $this->project = Project::findOrFail($id)->withCustomFields();
        $this->fields = $this->project->getCustomFieldGroupsWithFields()->fields;

        $isMember = ProjectMember::checkIsMember($id, $this->user->id);

        // Check authorised user

        if ($this->project->isProjectAdmin || $this->user->cans('view_projects') || $isMember) {
            $this->activeTimers = ProjectTimeLog::projectActiveTimers($this->project->id);

            $this->openTasks = Task::projectOpenTasks($this->project->id, $this->userDetail->id);
            $this->openTasksPercent = (count($this->openTasks) == 0 ? '0' : (count($this->openTasks) / count($this->project->tasks)) * 100);

            // TODO::ProjectDeadline to do
            $this->daysLeft = 0;
            $this->daysLeftFromStartDate = 0;
            $this->daysLeftPercent = 0;

            if ($this->project->deadline) {
                $this->daysLeft = $this->project->deadline->diff(Carbon::now())->format('%d') + ($this->project->deadline->diff(Carbon::now())->format('%m') * 30) + ($this->project->deadline->diff(Carbon::now())->format('%y') * 12);
                $this->daysLeftFromStartDate = $this->project->deadline->diff($this->project->start_date)->format('%d') + ($this->project->deadline->diff($this->project->start_date)->format('%m') * 30) + ($this->project->deadline->diff($this->project->start_date)->format('%y') * 12);
                $this->daysLeftPercent = ($this->daysLeftFromStartDate == 0 ? "0" : (($this->daysLeft / $this->daysLeftFromStartDate) * 100));
            }

            $this->hoursLogged = ProjectTimeLog::projectTotalMinuts($this->project->id);
            $minute = 0;
            $hour = intdiv($this->hoursLogged, 60);

            if (($this->hoursLogged % 60) > 0) {
                $minute = ($this->hoursLogged % 60);
                $this->hoursLogged = $hour . ':' . $minute;
            } else {
                $this->hoursLogged = $hour;
            }

            $this->recentFiles = ProjectFile::where('project_id', $this->project->id)->orderBy('id', 'desc')->limit(10)->get();
            $this->activities = ProjectActivity::getProjectActivities($id, 10, $this->userDetail->id);

            return view('member.projects.show', $this->data);
        } else {
            // If not authorised user
            abort(403);
        }
    }

    public function data(Request $request)
    {
        $this->userDetail = auth()->user();
        $projects = Project::selectRaw('projects.id, projects.project_name, projects.project_admin, projects.project_summary, projects.start_date, projects.deadline,
         projects.notes, projects.category_id, projects.client_id, projects.feedback, projects.completion_percent, projects.created_at, projects.updated_at,
          projects.status,
           ( select count("id") from pinned where pinned.project_id = projects.id and pinned.user_id = ' . user()->id . ') as pinned_project');
        if (!$this->user->cans('view_projects')) {
            $projects = $projects->join('project_members', 'project_members.project_id', '=', 'projects.id');
            $projects = $projects->where('project_members.user_id', '=', $this->userDetail->id);
        }

        if (!is_null($request->status) && $request->status != 'all') {
            if ($request->status == 'incomplete') {
                $projects->where('completion_percent', '<', '100');
            } elseif ($request->status == 'complete') {
                $projects->where('completion_percent', '=', '100');
            } else {
                $projects->where('status', '=', $request->status);
            }
        }


        if (!is_null($request->client_id) && $request->client_id != 'all') {
            $projects->where('client_id', $request->client_id);
        }

        $projects->groupBy('projects.id')->get();

        return DataTables::of($projects)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $action = '<div class="btn-group m-r-10">
                <button aria-expanded="false" data-toggle="dropdown" class="btn btn-info btn-outline  dropdown-toggle waves-effect waves-light" type="button">' . trans('app.action') . ' <span class="caret"></span></button>
                <ul role="menu" class="dropdown-menu">';

                if ($row->project_admin == $this->userDetail->id || $this->user->cans('edit_projects')) {
                    $action .= '<li><a href="' . route('member.projects.edit', [$row->id]) . '"><i class="fa fa-pencil" aria-hidden="true"></i> ' . trans('app.edit') . '</a></li>';
                }
                $action .= '<li><a href="' . route('member.projects.show', [$row->id]) . '"><i class="fa fa-search" aria-hidden="true"></i> ' . trans('app.view') . '</a></li>';
                $action .= '<li><a href="' . route('member.projects.gantt', [$row->id]) . '"><i class="fa fa-bar-chart" aria-hidden="true"></i> ' . trans('modules.projects.viewGanttChart') . '</a></li>';
                $action .= '<li><a href="' . route('front.gantt', [md5($row->id)]) . '" target="_blank"><i class="fa fa-line-chart" aria-hidden="true"></i> ' . trans('modules.projects.viewPublicGanttChart') . '</a></li>';

                if ($this->user->cans('delete_projects')) {
                    $action .= '<li><a href="javascript:;" data-user-id="' . $row->id . '" class="sa-params"><i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '</a></li>';
                }

                $action .= '</ul> </div>';

                return $action;
            })
            ->addColumn('members', function ($row) {
                $members = '';

                if (count($row->members) > 0) {
                    foreach ($row->members as $member) {
                        $members .= '<img data-toggle="tooltip" data-original-title="' . ucwords($member->user->name) . '" src="' . $member->user->image_url . '"
                        alt="user" class="img-circle" width="25" height="25"> ';
                    }
                } else {
                    $members .= __('messages.noMemberAddedToProject');
                }
                if ($this->user->cans('add_projects')) {
                    $members .= '<a class="btn btn-primary btn-circle" style="width: 25px;height: 25px;padding: 3px;" data-toggle="tooltip" data-original-title="' . __('modules.projects.addMemberTitle') . '"  href="' . route('member.project-members.show', $row->id) . '"><i class="fa fa-plus" ></i></a>';
                }
                return $members;
            })

            ->editColumn('project_name', function ($row) {
                $pin = '';
                if (($row->pinned_project)) {
                    $pin = '<br><span class="font-12"  data-toggle="tooltip" data-original-title="' . __('app.pinned') . '"><i class="icon-pin icon-2"></i></span>';
                }
                $name = ' <a href="' . route('member.projects.show', $row->id) . '">' . ucfirst($row->project_name) . '</a> ' . $pin;

                return $name;
            })
            ->editColumn('start_date', function ($row) {
                return $row->start_date->format($this->global->date_format);
            })
            ->editColumn('deadline', function ($row) {
                if ($row->deadline) {
                    return $row->deadline->format($this->global->date_format);
                }

                return '-';
            })
            ->editColumn('client_id', function ($row) {
                if (!is_null($row->client_id)) {
                    return ucwords($row->client->name);
                } else {
                    return '--';
                }
            })
            ->editColumn('completion_percent', function ($row) {
                if ($row->completion_percent < 50) {
                    $statusColor = 'danger';
                    $status = __('app.progress');
                } elseif ($row->completion_percent >= 50 && $row->completion_percent < 75) {
                    $statusColor = 'warning';
                    $status = __('app.progress');
                } else {
                    $statusColor = 'success';
                    $status = __('app.progress');

                    if ($row->completion_percent >= 100) {
                        $status = __('app.completed');
                    }
                }

                return '<h5>' . $status . '<span class="pull-right">' . $row->completion_percent . '%</span></h5><div class="progress">
                  <div class="progress-bar progress-bar-' . $statusColor . '" aria-valuenow="' . $row->completion_percent . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . $row->completion_percent . '%" role="progressbar"> <span class="sr-only">' . $row->completion_percent . '% Complete</span> </div>
                </div>';
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'in progress') {
                    $status = '<label class="label label-info">' . __('app.inProgress') . '</label>';
                } else if ($row->status == 'on hold') {
                    $status = '<label class="label label-warning">' . __('app.onHold') . '</label>';
                } else if ($row->status == 'not started') {
                    $status = '<label class="label label-warning">' . __('app.notStarted') . '</label>';
                } else if ($row->status == 'canceled') {
                    $status = '<label class="label label-danger">' . __('app.canceled') . '</label>';
                } else if ($row->status == 'finished') {
                    $status = '<label class="label label-success">' . __('app.finished') . '</label>';
                } else if ($row->status == 'under review') {
                    $status = '<label class="label label-warning">' . __('app.underReview') . '</label>';
                }
                return $status;
            })
            ->rawColumns(['project_name', 'action', 'members', 'completion_percent', 'status'])
            ->removeColumn('project_summary')
            ->removeColumn('notes')
            ->removeColumn('category_id')
            ->removeColumn('feedback')
            ->removeColumn('start_date')
            ->make(true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProject $request, $id)
    {
        $project = Project::findOrFail($id);
        $project->project_name = $request->project_name;
        if ($request->project_summary != '') {
            $project->project_summary = $request->project_summary;
        }
        $project->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');

        if (!$request->has('without_deadline')) {
            $project->deadline = Carbon::createFromFormat($this->global->date_format, $request->deadline)->format('Y-m-d');
        } else {
            $project->deadline = null;
        }

        if ($request->notes != '') {
            $project->notes = $request->notes;
        }
        if ($request->category_id != '') {
            $project->category_id = $request->category_id;
        }
        $project->client_id = ($request->client_id == 'null' || $request->client_id == '') ? null : $request->client_id;
        $project->feedback = $request->feedback;

        if ($request->calculate_task_progress) {
            $project->calculate_task_progress = $request->calculate_task_progress;
            $project->completion_percent = $this->calculateProjectProgress($id);
        } else {
            $project->calculate_task_progress = "false";
            $project->completion_percent = $request->completion_percent;
        }

        if ($request->client_view_task) {
            $project->client_view_task = 'enable';
        } else {
            $project->client_view_task = "disable";
        }
        if (($request->client_view_task) && ($request->client_task_notification)) {
            $project->allow_client_notification = 'enable';
        } else {
            $project->allow_client_notification = "disable";
        }

        if ($request->manual_timelog) {
            $project->manual_timelog = 'enable';
        } else {
            $project->manual_timelog = "disable";
        }
        $project->status = $request->status;

        $project->save();

        $this->logProjectActivity($project->id, ucwords($project->project_name) . __('modules.projects.projectUpdated'));
        return Reply::redirect(route('member.projects.edit', $id), __('messages.projectUpdated'));
    }

    public function create()
    {
       abort_if(!$this->user->cans('add_projects'),403);

        $this->clients = User::allClients();
        $this->categories = ProjectCategory::all();
        $this->templates = ProjectTemplate::all();
        $this->employees = User::allEmployees();

        $project = new Project();
        $this->fields = $project->getCustomFieldGroupsWithFields()->fields;
        $this->upload = can_upload();
        return view('member.projects.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProject $request)
    {
        $project = new Project();
        $project->project_name = $request->project_name;
        if ($request->project_summary != '') {
            $project->project_summary = $request->project_summary;
        }

        $project->start_date = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d');
        if (!$request->has('without_deadline')) {
            $project->deadline = Carbon::createFromFormat($this->global->date_format, $request->deadline)->format('Y-m-d');

        }

        if ($request->notes != '') {
            $project->notes = $request->notes;
        }
        if ($request->category_id != '') {
            $project->category_id = $request->category_id;
        }
        $project->client_id = $request->client_id;

        if ($request->client_view_task) {
            $project->client_view_task = 'enable';
        } else {
            $project->client_view_task = "disable";
        }
        if (($request->client_view_task) && ($request->client_task_notification)) {
            $project->allow_client_notification = 'enable';
        } else {
            $project->allow_client_notification = "disable";
        }

        if ($request->manual_timelog) {
            $project->manual_timelog = 'enable';
        } else {
            $project->manual_timelog = "disable";
        }

        $project->status = $request->status;

        $project->save();

        if ($request->template_id) {
            $template = ProjectTemplate::findOrFail($request->template_id);
            foreach ($template->members as $member) {
                $projectMember = new ProjectMember();

                $projectMember->user_id    = $member->user_id;
                $projectMember->project_id = $project->id;
                $projectMember->save();
            }
            foreach ($template->tasks as $task) {
                $projectTask = new Task();

                $projectTask->project_id  = $project->id;
                $projectTask->heading     = $task->heading;
                $projectTask->description = $task->description;
                $projectTask->due_date    = Carbon::now()->addDay()->format('Y-m-d');
                $projectTask->status      = 'incomplete';
                $projectTask->save();

                foreach ($task->users_many as $key => $value) {
                    TaskUser::create(
                        [
                            'user_id' => $value->id,
                            'task_id' => $projectTask->id
                        ]
                    );
                }

                foreach ($task->subtasks as $key => $value) {
                    SubTask::create(
                        [
                            'title' => $value->title,
                            'task_id' => $projectTask->id
                        ]
                    );
                }
            }
        }

        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $project->updateCustomFieldData($request->get('custom_fields_data'));
        }

        $users = $request->user_id;

        foreach ($users as $user) {
            $member = new ProjectMember();
            $member->user_id = $user;
            $member->project_id = $project->id;
            $member->save();

            $this->logProjectActivity($project->id, ucwords($member->user->name) . ' ' . __('messages.isAddedAsProjectMember'));
        }

        $this->logSearchEntry($project->id, 'Project: ' . $project->project_name, 'admin.projects.show', 'project');

        $this->logProjectActivity($project->id, ucwords($project->project_name) . ' ' . __("messages.addedAsNewProject"));

        return Reply::dataOnly(['projectID' => $project->id]);

        //        return Reply::redirect(route('member.projects.index'), __('modules.projects.projectUpdated'));
    }

    public function destroy($id)
    {
        Project::destroy($id);
        return Reply::success(__('messages.projectDeleted'));
    }

    public function gantt($ganttProjectId = '')
    {

        $data = array();
        $links = array();

        $projects = Project::select('projects.id', 'projects.project_name', 'projects.start_date', 'projects.deadline', 'projects.completion_percent');

        if (!$this->user->cans('view_projects')) {
            $projects = $projects->join('project_members', 'project_members.project_id', '=', 'projects.id');
            $projects = $projects->where('project_members.user_id', '=', $this->user->id);
        }

        if ($ganttProjectId != '') {
            $projects = $projects->where('projects.id', '=', $ganttProjectId);
        }

        $projects = $projects->get();

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
                'progress' => $project->completion_percent / 100
            ];

            $tasks = Task::projectOpenTasks($project->id);

            foreach ($tasks as $key => $task) {
                $id = $id + 1;

                $taskDuration = $task->due_date->diffInDays($task->start_date);
                $data[] = [
                    'id' => $id,
                    'text' => ucfirst($task->heading),
                    'start_date' => (!is_null($task->start_date)) ? $task->start_date->format('Y-m-d H:i:s') : $task->due_date->format('Y-m-d H:i:s'),
                    'duration' => $taskDuration,
                    'parent' => $projectId
                ];

                $links[] = [
                    'id' => $id,
                    'source' => $project->id,
                    'target' => $task->id,
                    'type' => 1
                ];
            }

            $ganttData = [
                'data' => $data,
                'links' => $links
            ];
        }

        $this->ganttProjectId = $ganttProjectId;
        $this->project = Project::findOrFail($ganttProjectId);
        return view('member.projects.gantt', $this->data);
    }

    public function ganttData($ganttProjectId = '')
    {
        $assignedTo = request('assignedTo');

        if ($assignedTo != 'all') {
            $tasks = Task::projectTasks($ganttProjectId, $assignedTo);
        } else {
            $tasks = Task::projectTasks($ganttProjectId);
        }

        
        $data = array();

        foreach ($tasks as $key => $task) {

            $data[] = [
                'id' => "task-" . $task->id,
                'name' => ucfirst($task->heading),
                'start' => (!is_null($task->start_date)) ? $task->start_date->format('Y-m-d') : $task->due_date->format('Y-m-d'),
                'end' => $task->due_date->format('Y-m-d'),
                'progress' => 0,
                'bg_color' => $task->board_column->label_color,
                'taskid' => $task->id,
                'draggable' => true
            ];

            if (!is_null($task->dependent_task_id)) {
                $data[$key]['dependencies'] = "task-" . $task->dependent_task_id;
            }
        }

        return response()->json($data);

        
    }

    public function updateTaskDuration(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->start_date = Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
        $task->due_date = Carbon::createFromFormat('d/m/Y', $request->end_date)->addDay()->format('Y-m-d');
        $task->save();

        return Reply::success('messages.taskUpdatedSuccessfully');
    }

    public function ajaxCreate(Request $request, $projectId = null)
    {

        $this->employees  = User::allEmployees();


        $projects = Project::select('projects.*');

        if (!$this->user->cans('view_projects')) {
            $projects = $projects->join('project_members', 'project_members.project_id', '=', 'projects.id');
            $projects = $projects->where('project_members.user_id', '=', $this->user->id);
        }

        $projects = $projects->get();

        if ($projectId) {
            $this->employees = ProjectMember::byProject($projectId);
            $this->projectId = $projectId;
            $this->pageName = 'ganttChart';
            $this->currentProject = $projects->filter(function ($value, $key) {
                return $value->id  == $this->projectId;
            })->first();
        }
        $this->taskBoardColumns = TaskboardColumn::all();

        $this->projects = $projects;
        $this->categories = TaskCategory::all();

        $this->parentGanttId = ($request->has('parent_gantt_id')) ? $request->parent_gantt_id : '';
        $completedTaskColumn = TaskboardColumn::where('slug', '!=', 'completed')->first();
        if ($completedTaskColumn) {
            $this->allTasks = Task::where('board_column_id', $completedTaskColumn->id)
                ->where('project_id', $projectId)
                ->get();
        } else {
            $this->allTasks = [];
        }
        return view('member.all-tasks.ajax_create', $this->data);
    }

    /**
     * Project discussions
     *
     * @param  int $projectId
     * @return \Illuminate\Http\Response
     */
    public function discussion(MemberDiscussionDataTable $dataTable, $projectId)
    {
        $this->project = Project::findOrFail($projectId);
        $this->discussionCategories = DiscussionCategory::orderBy('order', 'asc')->get();
        return $dataTable->with('project_id', $projectId)->render('member.projects.discussion.show', $this->data);
    }

    /**
     * Project discussions
     *
     * @param  int $projectId
     * @param  int $discussionId
     * @return \Illuminate\Http\Response
     */
    public function discussionReplies($projectId, $discussionId)
    {
        $this->project = Project::findOrFail($projectId);
        $this->discussion = Discussion::with('category')->findOrFail($discussionId);
        $this->discussionReplies = DiscussionReply::with('user')->where('discussion_id', $discussionId)->orderBy('id', 'asc')->get();
        return view('member.projects.discussion.replies', $this->data);
    }

    /**
     * @param $templateId
     * @return mixed
     */
    public function templateData($templateId)
    {
        $templateMember  = [];
        $projectTemplate = ProjectTemplate::with('members')->findOrFail($templateId);

        if ($projectTemplate->members) {
            $templateMember  = $projectTemplate->members->pluck('user_id')->toArray();
        }

        return Reply::dataOnly(['templateData' => $projectTemplate, 'member' => $templateMember]);
    }


    /**
     * @return mixed
     */
    public function pinnedItem()
    {
        $this->pinnedItems = Pinned::join('projects', 'projects.id', '=', 'pinned.project_id')
            ->where('pinned.user_id', '=', user()->id)
            ->select('projects.id', 'project_name')
            ->get();

        return view('member.projects.pinned-project', $this->data);
    }
}

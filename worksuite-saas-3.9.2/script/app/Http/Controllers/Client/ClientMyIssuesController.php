<?php

namespace App\Http\Controllers\Client;

use App\Helper\Reply;
use App\Http\Requests\Issue\StoreIssue;
use App\Issue;
use App\ModuleSetting;
use App\Notifications\NewIssue;
use App\Project;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;
use Yajra\DataTables\Facades\DataTables;

class ClientMyIssuesController extends ClientBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.issues';
        $this->pageIcon = 'ti-alert';
        $this->middleware(function ($request, $next) {
            if(!in_array('issues',$this->user->modules)){
                abort(403);
            }
            return $next($request);
        });

    }

    public function index() {
        return view('client.my-issues.index', $this->data);
    }

    public function create() {
        $this->projects = Project::clientProjects($this->user->id);
        return view('client.my-issues.create', $this->data);
    }

    public function store(StoreIssue $request) {
        $issue = new Issue();
        $issue->description = $request->description;
        $issue->user_id = $this->user->id;
        $issue->project_id = $request->project_id;
        $issue->save();

        // Notify admins
        $admins = User::whereHas('roles', function($q){
            $q->where('name', 'admin');
        })->get();
        Notification::send($admins, new NewIssue($issue));

        return Reply::redirect(route('client.my-issues.edit', $issue->id), __('messages.issueCreated'));
    }

    public function edit($id){
        $this->issue = Issue::findOrFail($id);
        $this->projects = Project::clientProjects($this->user->id);
        return view('client.my-issues.edit', $this->data);
    }

    public function update(StoreIssue $request, $id) {
        $issue = Issue::findOrFail($id);
        $issue->description = $request->description;
        $issue->project_id = $request->project_id;
        $issue->save();

        return Reply::redirect(route('client.my-issues.edit', $issue->id), __('messages.issueUpdated'));
    }

    public function destroy($id) {
        Issue::destroy($id);
        return Reply::success(__('messages.issueDeleted'));
    }

    public function data() {
        $invoices = Issue::join('projects', 'projects.id', '=', 'issues.project_id')
            ->select('issues.id', 'projects.project_name', 'issues.description', 'issues.status', 'issues.created_at')
            ->where('issues.user_id', $this->user->id);

        return DataTables::of($invoices)
            ->addColumn('action', function($row){
                return '<a href="'.route('client.my-issues.edit', [$row->id]).'" class="btn btn-info btn-circle"
                      data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>

                      <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-issue-id="'.$row->id.'" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
            })
            ->editColumn('created_at', function($row){
                return $row->created_at->format('d M, Y');
            })
            ->editColumn('status', function($row){
                if($row->status == 'resolved'){
                    return '<label class="label label-success">RESOLVED</label>';
                }
                elseif($row->status == 'pending'){
                    return '<label class="label label-warning">PENDING</label>';
                }
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }
}

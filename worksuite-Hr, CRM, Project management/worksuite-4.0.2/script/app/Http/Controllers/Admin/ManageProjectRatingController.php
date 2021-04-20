<?php

namespace App\Http\Controllers\Admin;

use App\Expense;
use App\Helper\Reply;
use App\Issue;
use App\ModuleSetting;
use App\Project;
use App\ProjectActivity;
use App\ProjectFile;
use App\ProjectRating;
use App\ProjectTimeLog;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ManageProjectRatingController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.projectRating';
        $this->pageIcon = 'icon-layers';
        $this->middleware(function ($request, $next) {
            if (!in_array('projects', $this->user->modules)) {
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
       // return view('client.projects.index', $this->data);
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
       $project = Project::findOrFail($request->project_id);
       $visible = $project->visible_rating_employee == 1 ? 0 : 1;

        $project->visible_rating_employee = $visible;
        $project->save();

        return Reply::success(__('messages.updateSuccess'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->userDetail = auth()->user();

        $this->project = Project::with('rating')->findOrFail($id)->withCustomFields();

        // Check authorised user
        return view('admin.projects.rating.show', $this->data);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rating = ProjectRating::findOrFail($id);
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
        $rating = ProjectRating::findOrFail($id);
        $rating->rating = $request->rating;
        $rating->comment = $request->comment;
        $rating->save();

        $this->rating = $rating;

        $view = view('client.project-rating.last-rating', $this->data)->render();

        return Reply::successWithData(__('messages.rating.updatedSuccess'), ['view' => $view]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ProjectRating::destroy($id);

        return Reply::success(__('messages.rating.deleteSuccess'));
    }

}

<?php

namespace App\Http\Controllers\Client;

use App\Project;
use App\Http\Requests\Milestone\StoreMilestone;
use App\ProjectMilestone;
use Yajra\DataTables\Facades\DataTables;
use App\Currency;

class ClientProjectMilestonesController extends ClientBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.projects';
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
    public function store(StoreMilestone $request)
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
        $this->project = Project::findorFail($id);
        $this->currencies = Currency::all();
        return view('client.project-milestones.show', $this->data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->milestone = ProjectMilestone::findOrFail($id);
        return view('client.project-milestones.detail', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreMilestone $request, $id)
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

    public function data($id)
    {
        $milestones = ProjectMilestone::with('currency')->where('project_id', $id)->get();

        return DataTables::of($milestones)
            ->editColumn('status', function ($row) {
                if ($row->status == 'complete') {
                    return '<label class="label label-success">' . trans('app.'.$row->status) . '</label>';
                } else {
                    return '<label class="label label-danger">' . trans('app.'.$row->status) . '</label>';
                }
            })
            ->editColumn('cost', function ($row) {
                if (!is_null($row->currency_id)) {
                    return $row->currency->currency_symbol . $row->cost;
                }
                return $row->cost;
            })
            ->editColumn('milestone_title', function ($row) {
                return '<a href="javascript:;" class="milestone-detail" data-milestone-id="' . $row->id . '">' . ucfirst($row->milestone_title) . '</a>';
            })
            ->addIndexColumn()
            ->rawColumns(['status', 'milestone_title'])
            ->removeColumn('project_id')
            ->make(true);
    }
}

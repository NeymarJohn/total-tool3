<?php

namespace App\Http\Controllers\Admin;

use App\Project;

class ManageProjectExpensesController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.projects';
        $this->pageIcon = 'icon-layers';
        $this->middleware(function ($request, $next) {
            abort_if(!in_array('expenses', $this->user->modules),403);
            return $next($request);
        });
    }

    public function show($id)
    {
        $this->project = Project::with('expenses', 'expenses.currency', 'expenses.user')->findorFail($id);
        return view('admin.projects.expenses.show', $this->data);
    }

   

   
}

<?php

namespace App\Http\Controllers\Client;

use App\Project;

class ClientProjectExpensesController extends ClientBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.projects';
        $this->pageIcon = 'icon-layers';
        $this->middleware(function ($request, $next) {
            if (!in_array('expenses', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function show($id)
    {
        $this->project = Project::with('expenses', 'expenses.currency', 'expenses.user')->findorFail($id);
        return view('client.project-expenses.show', $this->data);
    }

}
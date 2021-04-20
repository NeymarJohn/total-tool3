<?php

namespace App\Http\Controllers\Client;

use App\Project;

class ClientProjectPaymentsController extends ClientBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.projects';
        $this->pageIcon = 'icon-layers';
        $this->middleware(function ($request, $next) {
            if (!in_array('payments', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function show($id)
    {
        $this->project = Project::with('payments', 'payments.currency')->findorFail($id);
        return view('client.project-payments.show', $this->data);
    }

   

   
}

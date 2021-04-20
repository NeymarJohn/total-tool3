<?php

namespace App\Http\Controllers\Admin;

use App\Currency;
use App\Expense;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\Expenses\StoreExpense;
use App\Notifications\NewExpenseStatus;
use App\Project;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ManageProjectExpensesController extends AdminBaseController
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
        return view('admin.projects.expenses.show', $this->data);
    }

   

   
}

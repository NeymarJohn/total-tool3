<?php

namespace App\Http\Controllers\Admin;

use App\Currency;
use App\DataTables\Admin\ExpensesDataTable;
use App\DataTables\Admin\ExpensesRecurringDataTable;
use App\DataTables\Admin\RecurringExpensesDataTable;
use App\EmployeeDetails;
use App\Expense;
use App\ExpenseRecurring;
use App\ExpensesCategory;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\Expenses\StoreExpense;
use App\Http\Requests\Expenses\StoreRecurringExpense;
use App\Notifications\NewExpenseAdmin;
use App\Notifications\NewExpenseStatus;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ManageExpensesRecurringController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.expensesRecurring';
        $this->pageIcon = 'ti-shopping-cart';
        $this->middleware(function ($request, $next) {
            if (!in_array('expenses', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(ExpensesRecurringDataTable $dataTable)
    {
        if (!request()->ajax()) {
            $this->employees = User::allEmployees();
        }
        return $dataTable->render('admin.expenses-recurring.index', $this->data);
    }

    public function recurringExpenses(RecurringExpensesDataTable $dataTable, $id)
    {
//        dd($request->all());
        $this->expense = ExpenseRecurring::findOrFail($id);
        if (!request()->ajax()) {
            $this->employees = User::allEmployees();
        }

        return $dataTable->render('admin.expenses-recurring.recurring-expenses', $this->data);
    }

    public function create()
    {
        $this->currencies = Currency::all();
        $this->categories = ExpensesCategory::all();
        $this->employees = EmployeeDetails::select('id', 'user_id')
            ->with(['user' => function ($q) {
                $q->select('id', 'name');
            }])
            ->get();

        $employees = $this->employees->toArray();

        foreach ($this->employees as $employee) {
            $filtered_array = array_filter($employees, function ($item) use ($employee) {
                return $item['user']['id'] == $employee->user->id;
            });

            $projects = [];

            foreach ($employee->user->member as $member) {
                if (!is_null($member->project)) {
                    array_push($projects, $member->project()->select('id', 'project_name')->first()->toArray());
                }
            }
            $employees[key($filtered_array)]['user'] = array_add(reset($filtered_array)['user'], 'projects', $projects);
        }

        $this->employees = $employees;

        return view('admin.expenses-recurring.create', $this->data);
    }

    public function store(StoreRecurringExpense $request)
    {
        $expense = new ExpenseRecurring();
        $expense->item_name           = $request->item_name;
        $expense->price               = round($request->price, 2);
        $expense->currency_id         = $request->currency_id;
        $expense->category_id         = $request->category_id;
        $expense->user_id             = $request->user_id;
        $expense->status              = $request->status;
        $expense->rotation            = $request->rotation;
        $expense->billing_cycle       = $request->billing_cycle > 0 ? $request->billing_cycle : null;
        $expense->unlimited_recurring = $request->billing_cycle < 0  ? 1 : 0;
        $expense->description         = $request->description;
        $expense->created_by          = $this->user->id;

        if($request->rotation == 'weekly' || $request->rotation == 'bi-weekly'){
            $expense->day_of_week = $request->day_of_week;
        }
        elseif ($request->rotation == 'monthly' || $request->rotation == 'quarterly' || $request->rotation == 'half-yearly' || $request->rotation == 'annually'){
            $expense->day_of_month = $request->day_of_month;
        }

        if ($request->project_id > 0) {
            $expense->project_id = $request->project_id;
        }
        if ($request->hasFile('bill')) {
            $expense->bill = $request->bill->hashName();
            $request->bill->store('expense-invoice');
        }
        $expense->status = 'active';
        $expense->save();
        return Reply::redirect(route('admin.expenses-recurring.index'), __('messages.expenseSuccess'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $this->currencies = Currency::all();
        $this->expense = ExpenseRecurring::findOrFail($id);
        $this->categories = ExpensesCategory::all();

        $this->employees = EmployeeDetails::select('id', 'user_id')
            ->with(['user' => function ($q) {
                $q->select('id', 'name');
            }])
            ->get();

        $employees = $this->employees->toArray();

        foreach ($this->employees as $employee) {
            $filtered_array = array_filter($employees, function ($item) use ($employee) {
                return $item['user']['id'] == $employee->user->id;
            });

            $projects = [];

            foreach ($employee->user->member as $member) {
                if (!is_null($member->project)) {
                    array_push($projects, $member->project()->select('id', 'project_name')->first()->toArray());
                }
            }
            $employees[key($filtered_array)]['user'] = array_add(reset($filtered_array)['user'], 'projects', $projects);
        }

        $this->employees = $employees;
        return view('admin.expenses-recurring.edit', $this->data);
    }

    public function update(StoreRecurringExpense $request, $id)
    {
        $expense = expenseRecurring::findOrFail($id);
        $expense->item_name           = $request->item_name;
        $expense->price               = round($request->price, 2);
        $expense->currency_id         = $request->currency_id;
        $expense->category_id         = $request->category_id;
        $expense->user_id             = $request->user_id;
        $expense->status              = $request->status;
        $expense->rotation            = $request->rotation;
        $expense->billing_cycle       = $request->billing_cycle > 0 ? $request->billing_cycle : null;
        $expense->unlimited_recurring = $request->billing_cycle < 0  ? 1 : 0;
        $expense->description         = $request->description;

        if($request->rotation == 'weekly' || $request->rotation == 'bi-weekly'){
            $expense->day_of_week = $request->day_of_week;
        }
        elseif ($request->rotation == 'monthly' || $request->rotation == 'quarterly' || $request->rotation == 'half-yearly' || $request->rotation == 'annually'){
            $expense->day_of_month = $request->day_of_month;
        }


        if ($request->project_id > 0) {
            $expense->project_id = $request->project_id;
        }

        if ($request->hasFile('bill')) {
            Files::deleteFile($expense->bill, 'expense-invoice');
            $expense->bill = $request->bill->hashName();
            $request->bill->store('expense-invoice');
        }
        $expense->save();

        return Reply::redirect(route('admin.expenses-recurring.index'), __('messages.expenseUpdateSuccess'));
    }

    public function destroy($id)
    {
        ExpenseRecurring::destroy($id);
        return Reply::success(__('messages.expenseDeleted'));
    }

    public function show($id)
    {
        $this->expense = expenseRecurring::with(['user','recurrings'])->findOrFail($id);

        return view('admin.expenses-recurring.show', $this->data);
    }

    public function changeStatus(Request $request)
    {
        $expenseId = $request->expenseId;
        $status = $request->status;
        $expense = expenseRecurring::findOrFail($expenseId);
        $expense->status = $status;
        $expense->save();
        return Reply::success(__('messages.updateSuccess'));
    }

    public function download($id) {
        $expense = ExpenseRecurring::findOrFail($id);
        return download_local_s3($expense, 'expense-invoice/'.$expense->bill);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Currency;
use App\DataTables\Admin\ExpensesDataTable;
use App\EmployeeDetails;
use App\Expense;
use App\ExpensesCategory;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\Expenses\StoreExpense;
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

class ManageExpensesController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.expenses';
        $this->pageIcon = 'ti-shopping-cart';
        $this->middleware(function ($request, $next) {
            if (!in_array('expenses', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index(ExpensesDataTable $dataTable)
    {
        if (!request()->ajax()) {
            $this->employees = User::allEmployees();
        }
        return $dataTable->render('admin.expenses.index', $this->data);
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

        $expense = new Expense();
        $this->fields = $expense->getCustomFieldGroupsWithFields()->fields;
        return view('admin.expenses.create', $this->data);
    }

    public function store(StoreExpense $request)
    {
        $expense = new Expense();
        $expense->item_name           = $request->item_name;
        $expense->purchase_date       = Carbon::createFromFormat($this->global->date_format, $request->purchase_date)->format('Y-m-d');
        $expense->purchase_from       = $request->purchase_from;
        $expense->price               = round($request->price, 2);
        $expense->currency_id         = $request->currency_id;
        $expense->category_id         = $request->category_id;
        $expense->user_id             = $request->user_id;
        $expense->status              = $request->status;

        if ($request->project_id > 0) {
            $expense->project_id = $request->project_id;
        }
        if ($request->hasFile('bill')) {
            $expense->bill = $request->bill->hashName();
            $request->bill->store('expense-invoice');
        }

        $expense->status = 'approved';
        $expense->save();

        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $expense->updateCustomFieldData($request->get('custom_fields_data'));
        }

        return Reply::redirect(route('admin.expenses.index'), __('messages.expenseSuccess'));
    }

    public function edit($id)
    {
        $this->currencies = Currency::all();
        $this->expense = Expense::findOrFail($id)->withCustomFields();
        $this->fields = $this->expense->getCustomFieldGroupsWithFields()->fields;
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
        return view('admin.expenses.edit', $this->data);
    }

    public function update(StoreExpense $request, $id)
    {
        $expense = Expense::findOrFail($id);
        $expense->item_name = $request->item_name;
        $expense->purchase_date = Carbon::createFromFormat($this->global->date_format, $request->purchase_date)->format('Y-m-d');
        $expense->purchase_from = $request->purchase_from;
        $expense->price = round($request->price, 2);
        $expense->currency_id = $request->currency_id;
        $expense->user_id = $request->user_id;
        $expense->category_id = $request->category_id;

        if ($request->project_id > 0) {
            $expense->project_id = $request->project_id;
        } else {
            $expense->project_id = null;
        }

        if ($request->hasFile('bill')) {
            Files::deleteFile($expense->bill, 'expense-invoice');
            $expense->bill = $request->bill->hashName();
            $request->bill->store('expense-invoice');
            // $img = Image::make('user-uploads/expense-invoice/' . $expense->bill);
            // $img->resize(500, null, function ($constraint) {
            //     $constraint->aspectRatio();
            // });
            // $img->save();
        }

        $expense->status = $request->status;
        $expense->save();

        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $expense->updateCustomFieldData($request->get('custom_fields_data'));
        }

        return Reply::redirect(route('admin.expenses.index'), __('messages.expenseUpdateSuccess'));
    }

    public function destroy($id)
    {
        Expense::destroy($id);
        return Reply::success(__('messages.expenseDeleted'));
    }

    public function show($id)
    {
        $this->expense = Expense::with('user')->findOrFail($id)->withCustomFields();
        $this->fields = $this->expense->getCustomFieldGroupsWithFields()->fields;
        return view('admin.expenses.show', $this->data);
    }

    public function changeStatus(Request $request)
    {
        $expenseId = $request->expenseId;
        $status = $request->status;
        $expense = Expense::findOrFail($expenseId);
        $expense->status = $status;
        $expense->save();
        return Reply::success(__('messages.updateSuccess'));
    }
}

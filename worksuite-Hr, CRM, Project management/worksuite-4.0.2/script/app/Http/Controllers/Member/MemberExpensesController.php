<?php

namespace App\Http\Controllers\Member;

use App\Currency;
use App\Expense;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\Member\Expenses\StoreExpense;
use App\Notifications\NewExpenseMember;
use App\Project;
use App\ExpensesCategory;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class MemberProjectsController
 * @package App\Http\Controllers\Member
 */
class MemberExpensesController extends MemberBaseController
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

    public function index()
    {
        return view('member.expenses.index', $this->data);
    }

    public function data(Request $request)
    {

        $payments = Expense::with(['user']);
        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $payments = $payments->where(DB::raw('DATE(expenses.`purchase_date`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $payments = $payments->where(DB::raw('DATE(expenses.`purchase_date`)'), '<=', $endDate);
        }

        if ($request->status != 'all' && !is_null($request->status)) {
            $payments = $payments->where('expenses.status', '=', $request->status);
        }

        if (!$this->user->can('view_expenses')) {
            $payments = $payments->where('expenses.user_id', '=', $this->user->id);
        }

        $payments = $payments->orderBy('id', 'desc')->get();

        $dataTable =  DataTables::of($payments)
            ->addColumn('action', function ($row) {
                $html = '';

                if ($row->status == 'pending' && $this->user->can('edit_expenses')) {
                    $html .= '<a href="' . route("member.expenses.edit", $row->id) . '" data-toggle="tooltip" data-original-title="Edit" class="btn btn-info btn-circle"><i class="fa fa-pencil"></i></a>&nbsp;&nbsp;';
                }
                if ($this->user->can('delete_expenses')) {
                    $html .= '<a href="javascript:;" data-toggle="tooltip" data-original-title="Delete" data-expense-id="' . $row->id . '" class="btn btn-danger btn-circle sa-params"><i class="fa fa-times"></i></a>';
                }

                if ($html == '') {
                    $html = '--';
                }

                return $html;
            })
            ->editColumn('price', function ($row) {
                return  $row->currency->currency_symbol . ' ' . $row->price;
            })
            ->editColumn('user_id', function ($row) {
                return $row->user->name;
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'pending') {
                    return '<label class="label label-warning">' . strtoupper($row->status) . '</label>';
                } else if ($row->status == 'approved') {
                    return '<label class="label label-success">' . strtoupper($row->status) . '</label>';
                } else {
                    return '<label class="label label-danger">' . strtoupper($row->status) . '</label>';
                }
            })
            ->editColumn(
                'purchase_date',
                function ($row) {
                    if (!is_null($row->purchase_date)) {
                        return $row->purchase_date->format($this->global->date_format);
                    }
                }
            )
            ->addIndexColumn()
            ->rawColumns(['action', 'status', 'user_id'])
            ->removeColumn('currency_id')
            ->removeColumn('bill')
            ->removeColumn('purchase_from')
            ->removeColumn('updated_at')
            ->removeColumn('created_at');
        if (!$this->user->can('view_expenses')) {
            $dataTable = $dataTable->removeColumn('user_id');
        }
        $dataTable = $dataTable->make(true);
        return $dataTable;
    }

    public function create()
    {
        $this->currencies = Currency::all();
        $this->categories = ExpensesCategory::all();
        if ($this->user->can('view_projects')) {
            $this->projects = Project::select('id', 'project_name')->get();
        } else {
            $this->projects = Project::join('project_members', 'projects.id', 'project_members.project_id')
                ->where('user_id', $this->user->id)
                ->select('projects.id', 'projects.project_name')
                ->get();
        }
        $this->employees = User::allEmployees();

        $expense = new Expense();
        $this->fields = $expense->getCustomFieldGroupsWithFields()->fields;
        return view('member.expenses.create', $this->data);
    }

    public function store(StoreExpense $request)
    {

        $expense = new Expense();
        $expense->item_name = $request->item_name;
        $expense->purchase_date = Carbon::createFromFormat($this->global->date_format, $request->purchase_date)->format('Y-m-d');
        $expense->purchase_from = $request->purchase_from;
        $expense->price = round($request->price, 2);
        $expense->currency_id = $request->currency_id;
        $expense->category_id = $request->category_id;

        if ($request->project_id > 0) {
            $expense->project_id = $request->project_id;
        }

        if ($this->user->can('add_expenses')) {
            $expense->user_id = $request->employee;
        } else {
            $expense->user_id = auth()->user()->id;
        }

        if ($request->hasFile('bill')) {
            $expense->bill = $request->bill->hashName();
            $request->bill->store('expense-invoice');
            // $img = Image::make('user-uploads/expense-invoice/' . $expense->bill);
            // $img->resize(500, null, function ($constraint) {
            //     $constraint->aspectRatio();
            // });
            // $img->save();
        }

        $expense->status = 'pending';
        $expense->save();

        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $expense->updateCustomFieldData($request->get('custom_fields_data'));
        }

        return Reply::redirect(route('member.expenses.index'), __('messages.expenseSuccess'));
    }

    public function edit($id)
    {
        if (!$this->user->can('edit_expenses')) {
            abort(403);
        }
        $this->expense = Expense::findOrFail($id)->withCustomFields();
        $this->fields = $this->expense->getCustomFieldGroupsWithFields()->fields;

        if ($this->expense->status != 'pending') {
            abort(403);
        }

        if ($this->user->can('view_projects')) {
            $this->projects = Project::select('id', 'project_name')->get();
        } else {
            $this->projects = Project::join('project_members', 'projects.id', 'project_members.project_id')
                ->where('user_id', $this->user->id)
                ->select('projects.id', 'projects.project_name')
                ->get();
        }

        $this->currencies = Currency::all();
        $this->employees = User::allEmployees();

        return view('member.expenses.edit', $this->data);
    }

    public function update(StoreExpense $request, $id)
    {
        if (!$this->user->can('edit_expenses')) {
            abort(403);
        }
        $expense = Expense::findOrFail($id);

        if ($expense->status != 'pending') {
            return Reply::error(__('messages.unAuthorisedUser'));
        }

        $expense->item_name = $request->item_name;
        $expense->purchase_date = Carbon::createFromFormat($this->global->date_format, $request->purchase_date)->format('Y-m-d');
        $expense->purchase_from = $request->purchase_from;
        $expense->price = round($request->price, 2);
        $expense->currency_id = $request->currency_id;

        if ($this->user->can('add_expenses')) {
            $expense->user_id = $request->employee;
        }

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

        $expense->save();

        // To add custom fields data
        if ($request->get('custom_fields_data')) {
            $expense->updateCustomFieldData($request->get('custom_fields_data'));
        }

        return Reply::redirect(route('member.expenses.index'), __('messages.expenseUpdateSuccess'));
    }

    public function destroy($id)
    {
        if (!$this->user->can('delete_expenses')) {
            abort(403);
        }
        Expense::destroy($id);
        return Reply::success(__('messages.expenseDeleted'));
    }
}

<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\Estimate;
use App\Expense;
use App\ExpenseRecurring;
use App\Invoice;
use App\Payment;
use App\Task;
use App\TaskboardColumn;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class ExpensesRecurringDataTable extends BaseDataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', function ($row) {
                $action = '<div class="btn-group dropdown m-r-10">
                <button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle waves-effect waves-light" type="button"><i class="fa fa-gears "></i></button>
                <ul role="menu" class="dropdown-menu pull-right">
                    <li><a href="' . route("admin.expenses-recurring.show", $row->id) . '"><i class="fa fa-search" aria-hidden="true"></i> ' . trans('app.view') . '</a></li>
                    <li><a href="' . route("admin.expenses-recurring.edit", $row->id) . '"><i class="fa fa-pencil" aria-hidden="true"></i> ' . trans('app.edit') . '</a></li>
                    <li><a href="javascript:;"  data-expense-id="' . $row->id . '" class="delete-expense"><i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '</a></li>';

                $action .= '</ul> </div>';

                return $action;
            })
            ->editColumn('price', function ($row) {
                return $row->total_amount;
            })
            ->editColumn('user_id', function ($row) {
                return '<a href="' . route('admin.employees.show', $row->user_id) . '">' . ucwords($row->name) . '</a>';
            })
            ->editColumn('status', function ($row) {
                $status = '<div class="btn-group dropdown">';
                if ($row->status == 'active') {
                    $status .= '<button aria-expanded="true" data-toggle="dropdown" class="btn dropdown-toggle waves-effect waves-light btn-xs btn-success" type="button">' . ucfirst($row->status) . ' <span class="caret"></span></button>';
                } else {
                    $status .= '<button aria-expanded="true" data-toggle="dropdown" class="btn dropdown-toggle waves-effect waves-light btn-xs btn-danger" type="button">' . ucfirst($row->status) . ' <span class="caret"></span></button>';
                }
                $status .= '<ul role="menu" class="dropdown-menu pull-right">';
                $status .= '<li><a href="javascript:;" data-expense-id="' . $row->id . '" class="change-status" data-status="active">' . __('app.active') . '</a></li>';
                $status .= '<li><a href="javascript:;" data-expense-id="' . $row->id . '" class="change-status" data-status="inactive">' . __('app.inactive') . '</a></li>';
                $status .= '</ul>';
                $status .= '</div>';
                return $status;
            })
            ->addColumn('status_export', function ($row) {
                return ucfirst($row->status);
            })
            ->editColumn('item_name', function ($row) {
                return  '<a href="' . route("admin.expenses-recurring.show", $row->id) . '" data-expense-id="' . $row->id . '" >' . ucfirst($row->item_name) . '</a>';
            })
            ->editColumn(
                'created_at',
                function ($row) {
                    if (!is_null($row->created_at)) {
                        return $row->created_on;
                    }
                    return '- -';
                }
            )
            ->addIndexColumn()
            ->rawColumns(['action', 'status', 'user_id', 'created_at', 'item_name'])
            ->removeColumn('currency_id')
            ->removeColumn('name')
            ->removeColumn('currency_symbol')
            ->removeColumn('updated_at');

    }

    public function ajax()
    {
        return $this->dataTable($this->query())
            ->make(true);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        $request = $this->request();

        $model = ExpenseRecurring::select('expenses_recurring.id', 'expenses_recurring.item_name', 'expenses_recurring.user_id', 'expenses_recurring.created_at', 'expenses_recurring.price', 'users.name', 'expenses_recurring.currency_id', 'currencies.currency_symbol', 'expenses_recurring.status')
            ->join('users', 'users.id', 'expenses_recurring.user_id')
            ->join('currencies', 'currencies.id', 'expenses_recurring.currency_id');

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $model = $model->where(DB::raw('DATE(expenses_recurring.`created_at`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $model = $model->where(DB::raw('DATE(expenses_recurring.`created_at`)'), '<=', $endDate);
        }

        if ($request->status != 'all' && !is_null($request->status)) {
            $model = $model->where('expenses_recurring.status', '=', $request->status);
        }
        if ($request->employee != 'all' && !is_null($request->employee)) {
            $model = $model->where('expenses_recurring.user_id', '=', $request->employee);
        }

        return $model;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('expenses-recurring-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-6'l><'col-md-6'Bf>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>")
            ->orderBy(0,'desc')
            ->destroy(true)
            ->responsive(true)
            ->serverSide(true)
            ->stateSave(true)
            ->processing(true)
            ->language(__("app.datatable"))
            ->buttons(
                Button::make(['extend' => 'export', 'buttons' => ['excel', 'csv'], 'text' => '<i class="fa fa-download"></i> ' . trans('app.exportExcel') . '&nbsp;<span class="caret"></span>'])
            )
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["expenses-recurring-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    })
                }',
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false ],
            __('modules.expensesRecurring.itemName')  => ['data' => 'item_name', 'name' => 'item_name'],
            __('app.price') => ['data' => 'price', 'name' => 'price'],
            __('app.menu.employees') => ['data' => 'user_id', 'name' => 'user_id'],
            __('modules.expensesRecurring.created_at') => ['data' => 'created_at', 'name' => 'created_at', 'visible' => true],
            __('app.status') => ['data' => 'status', 'name' => 'status'],
//            __('app.status') => ['data' => 'status', 'name' => 'status', 'exportable' => false],
//            __('app.status') => ['data' => 'status_export', 'name' => 'status_export', 'visible' => false],
            Column::computed('action', __('app.action'))
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->width(150)
                ->addClass('text-center')
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Expenses_Recurring_' . date('YmdHis');
    }

    public function pdf()
    {
        set_time_limit(0);
        if ('snappy' == config('datatables-buttons.pdf_generator', 'snappy')) {
            return $this->snappyPdf();
        }

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('datatables::print', ['data' => $this->getDataForPrint()]);

        return $pdf->download($this->getFilename() . '.pdf');
    }
}

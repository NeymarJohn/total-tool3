<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\Estimate;
use App\Expense;
use App\Invoice;
use App\Payment;
use App\Task;
use App\TaskboardColumn;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class RecurringExpensesDataTable extends BaseDataTable
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
                $action = '<a href="javascript:;" title="'.__('app.view').'" data-expense-id="' . $row->id . '" class="view-expense"><i class="fa fa-search" aria-hidden="true"></i> </a>';

                return $action;
            })
            ->editColumn('price', function ($row) {
                return $row->total_amount;
            })
            ->editColumn('user_id', function ($row) {
                return '<a href="' . route('admin.employees.show', $row->user_id) . '">' . ucwords($row->name) . '</a>';
            })
            ->editColumn('status', function ($row) {
                $status = '';

                if ($row->status == 'pending') {
                    $status .= '<label class="label label-danger">'.$row->status.'</label>';
                } else  {
                    $status .= '<label class="label label-success">'.$row->status.'</label>';
                }

                return $status;
            })
            ->addColumn('status_export', function ($row) {
                return ucfirst($row->status);
            })
            ->editColumn(
                'purchase_date',
                function ($row) {
                    if (!is_null($row->purchase_date)) {
                        return $row->purchase_date->timezone($this->global->timezone)->format($this->global->date_format);
                    }
                }
            )
            ->addIndexColumn()
            ->rawColumns(['action', 'status', 'user_id'])
            ->removeColumn('currency_id')
            ->removeColumn('name')
            ->removeColumn('currency_symbol')
            ->removeColumn('updated_at')
            ->removeColumn('created_at');

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

        $model = Expense::with('currency')->select('expenses.id', 'expenses.item_name', 'expenses.user_id', 'expenses.price', 'users.name', 'expenses.purchase_date', 'expenses.currency_id', 'currencies.currency_symbol', 'expenses.status', 'expenses.purchase_from')
            ->join('users', 'users.id', 'expenses.user_id')
            ->join('currencies', 'currencies.id', 'expenses.currency_id')
            ->join('expenses_recurring', 'expenses_recurring.id', 'expenses.expenses_recurring_id')
            ->where('expenses.expenses_recurring_id', '=', $request->recurringID);


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
            ->setTableId('recurring-expenses-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom("<'row'<'col-md-6'l><'col-md-6'Bf>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>")
            ->orderBy(0)
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
                   window.LaravelDataTables["recurring-expenses-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    })
                }',
                'order' => [
                    5, 
                    'desc']
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
            __('modules.expenses.itemName')  => ['data' => 'item_name', 'name' => 'item_name'],
            __('app.price') => ['data' => 'price', 'name' => 'price'],
            __('app.menu.employees') => ['data' => 'user_id', 'name' => 'user_id'],
            __('modules.expenses.purchaseDate') => ['data' => 'purchase_date', 'name' => 'purchase_date'],
            __('app.status') => ['data' => 'status', 'name' => 'status', 'exportable' => false],
            __('app.status') => ['data' => 'status_export', 'name' => 'status_export', 'visible' => false],
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
        return 'Recurring_Expenses_' . date('YmdHis');
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

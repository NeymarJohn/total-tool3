<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\Invoice;
use App\InvoiceSetting;
use App\RecurringInvoice;
use App\Scopes\CompanyScope;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class InvoiceRecurringDataTable extends BaseDataTable
{
    protected $firstInvoice;
    protected $invoiceSettings;
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
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $action = '<a href="' . route('admin.invoice-recurring.show', $row->id) . '"><i class="fa fa-search"></i>' . __('app.view') . '</a>';

                return $action;
            })
            ->addColumn('action', function ($row)  {
                $action = '<div class="btn-group dropdown m-r-10">
                 <button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle waves-effect waves-light" type="button"><i class="fa fa-gears "></i></button>
                <ul role="menu" class="dropdown-menu">';

                $action .= '<li><a href="' . route("admin.invoice-recurring.show", $row->id) . '"><i class="fa fa-search"></i> ' . __('app.view') . '</a></li>';
                $action .= '<li><a href="' . route("admin.invoice-recurring.edit", $row->id) . '"><i class="fa fa-pencil"></i> ' . __('app.edit') . '</a></li>';

                $action .= '<li><a href="javascript:;" data-toggle="tooltip"  data-invoice-id="' . $row->id . '" class="sa-params"><i class="fa fa-times"></i> ' . __('app.delete') . '</a></li>';

                $action .= '</ul> </div>';

                return $action;
            })
            ->editColumn('project_name', function ($row) {
                if ($row->project_id != null) {
                    return '<a href="' . route('admin.projects.show', $row->project_id) . '">' . ucfirst($row->project->project_name) . '</a>';
                }

                return '--';
            })
            ->editColumn('name', function ($row) {
                if ($row->project && $row->project->client) {
                    return ucfirst($row->project->client->name);
                }
                if ($row->client_id != '') {
                    $client = User::withoutGlobalScope(CompanyScope::class)->find($row->client_id);
                    if ($client) {
                        return ucfirst($client->name);
                    }

                    return '--';
                }

                return '--';
            })
            ->editColumn('status', function ($row) {
                $status = '<div class="btn-group dropdown">';
                if ($row->status == 'active') {
                    $status .= '<button aria-expanded="true" data-toggle="dropdown" class="btn dropdown-toggle waves-effect waves-light btn-xs btn-success" type="button">' . ucfirst($row->status) . ' <span class="caret"></span></button>';
                } else {
                    $status .= '<button aria-expanded="true" data-toggle="dropdown" class="btn dropdown-toggle waves-effect waves-light btn-xs btn-danger" type="button">' . ucfirst($row->status) . ' <span class="caret"></span></button>';
                }
                $status .= '<ul role="menu" class="dropdown-menu pull-right">';
                $status .= '<li><a href="javascript:;" data-invoice-id="' . $row->id . '" class="change-status" data-status="active">' . __('app.active') . '</a></li>';
                $status .= '<li><a href="javascript:;" data-invoice-id="' . $row->id . '" class="change-status" data-status="inactive">' . __('app.inactive') . '</a></li>';
                $status .= '</ul>';
                $status .= '</div>';
                return $status;
            })
            ->editColumn('total', function ($row) {
                $currencyCode = ' (' . $row->currency->currency_code . ') ';
                $currencySymbol = $row->currency->currency_symbol;

                return '<div class="text-right">' . __('app.total') . ': ' . $currencySymbol . $row->total . '</div>';
            })
            ->editColumn(
                'issue_date',
                function ($row) {
                    return $row->issue_date->timezone($this->global->timezone)->format($this->global->date_format);
                }
            )
            ->rawColumns(['project_name', 'action', 'status', 'total'])
            ->removeColumn('currency_symbol')
            ->removeColumn('currency_code')
            ->removeColumn('project_id');
    }

    /**
     * @param RecurringInvoice $model
     * @return $this|RecurringInvoice
     */
    public function query(RecurringInvoice $model)
    {
        $request = $this->request();

        $model = $model->with(['project' => function ($q) {
            $q->withTrashed();
            $q->select('id', 'project_name', 'client_id');
        }, 'currency:id,currency_symbol,currency_code', 'project.client'])
            ->select('invoice_recurring.id', 'invoice_recurring.project_id', 'invoice_recurring.client_id', 'invoice_recurring.currency_id', 'invoice_recurring.total', 'invoice_recurring.status', 'invoice_recurring.issue_date', 'invoice_recurring.show_shipping_address');

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $model = $model->where(DB::raw('DATE(invoice_recurring.`issue_date`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $model = $model->where(DB::raw('DATE(invoice_recurring.`issue_date`)'), '<=', $endDate);
        }

        if ($request->status != 'all' && !is_null($request->status)) {
            $model = $model->where('invoice_recurring.status', '=', $request->status);
        }

        if ($request->projectID != 'all' && !is_null($request->projectID)) {
            $model = $model->where('invoice_recurring.project_id', '=', $request->projectID);
        }

        if ($request->clientID != 'all' && !is_null($request->clientID)) {
            $model = $model->where('invoice_recurring.client_id', '=', $request->clientID);
        }

        $model = $model->whereHas('project', function ($q) {
            $q->whereNull('deleted_at');
        }, '>=', 0);
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
            ->setTableId('invoices-recurring-table')
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
                   window.LaravelDataTables["invoices-recurring-table"].buttons().container()
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
        $modules = $this->user->modules;

        $dsData =  [
            __('app.id') => ['data' => 'id', 'name' => 'id', 'visible' => false],
            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
            __('app.client') => ['data' => 'name', 'name' => 'project.client.name'],
            __('modules.invoices.total') => ['data' => 'total', 'name' => 'total'],
            __('modules.invoices.invoiceDate') => ['data' => 'issue_date', 'name' => 'issue_date'],
            __('app.status') => ['data' => 'status', 'name' => 'status'],
            Column::computed('action', __('app.action'))
                ->exportable(false)
                ->printable(false)
                ->orderable(false)
                ->searchable(false)
                ->width(150)
                ->addClass('text-center')
        ];
        if(in_array('projects', $modules) ){
            $dsData = array_slice($dsData, 0, 3, true) +
                [            __('app.project')  => ['data' => 'project_name', 'name' => 'project.project_name']] +
                array_slice($dsData, 3, count($dsData) - 1, true) ;
        }

        return $dsData;
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Invoices_recurring_' . date('YmdHis');
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

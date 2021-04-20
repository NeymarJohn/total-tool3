<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class PaymentsDataTable extends BaseDataTable
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
                <li><a href="javascript:;" class="view-payment" data-payment-id="' . $row->id . '"><i class="fa fa-eye" aria-hidden="true"></i> ' . trans('app.view') . '</a></li>';
                if (!is_null($row->bill)) {
                    $action .= '<li><a target="_blank"
                      href="' . $row->file_url . '"><i class="fa fa-file" aria-hidden="true"></i> ' . __('app.view') . ' ' . __('app.receipt') . '</a></li>';
                }
                $action .= '<li><a href="' . route("admin.payments.edit", $row->id) . '"><i class="fa fa-pencil" aria-hidden="true"></i> ' . trans('app.edit') . '</a></li>
                  <li><a href="javascript:;" data-payment-id="' . $row->id . '" class="sa-params"><i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '</a></li>';

                $action .= '</ul> </div>';

                return $action;
            })
            ->editColumn('remarks', function ($row) {
                return ucfirst($row->remarks);
            })

            ->editColumn('project_id', function ($row) {
                if (!is_null($row->project)) {
                    return '<a href="' . route('admin.projects.show', $row->project_id) . '">' . ucfirst($row->project->project_name) . '</a>';
                } 
                return '--';
            })
            ->editColumn('invoice_number', function ($row) {
                if ($row->invoice_id != null && !is_null($row->invoice)) {
                    return '<a href="javascript:;" class="view-payment" data-payment-id="' . $row->id . '">'.$row->invoice->invoice_number.'</a>';
                } 
                return '--';
            })
           
            ->editColumn('status', function ($row) {
                if ($row->status == 'pending') {
                    return '<label class="label label-warning">' . strtoupper($row->status) . '</label>';
                } else {
                    return '<label class="label label-success">' . strtoupper($row->status) . '</label>';
                }
            })
            ->editColumn('amount', function ($row) {
                $symbol = (!is_null($row->currency)) ? $row->currency->currency_symbol : '';
                $code = (!is_null($row->currency)) ? $row->currency->currency_code : '';
                return currency_position($row->amount,$symbol). ' (' . $code . ')';
            })
            ->editColumn(
                'paid_on',
                function ($row) {
                    if (!is_null($row->paid_on)) {
                        return $row->paid_on->format($this->global->date_format . ' ' . $this->global->time_format);
                    }
                }
            )
            ->addIndexColumn()
            ->rawColumns(['invoice', 'action', 'status', 'project_id','invoice_number'])
            ->removeColumn('invoice_id')
            ->removeColumn('currency_symbol')
            ->removeColumn('currency_code')
            ->removeColumn('project_name');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Payment $model)
    {
        $request = $this->request();

        $model = $model->with(['project:id,project_name', 'currency:id,currency_symbol,currency_code', 'invoice'])
            ->leftJoin('projects', 'projects.id', '=', 'payments.project_id')
            ->select('payments.id', 'payments.project_id', 'payments.currency_id', 'payments.invoice_id', 'payments.amount', 'payments.status', 'payments.paid_on', 'payments.remarks', 'payments.bill');

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $model = $model->where(DB::raw('DATE(payments.`paid_on`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $model = $model->where(DB::raw('DATE(payments.`paid_on`)'), '<=', $endDate);
        }

        if ($request->status != 'all' && !is_null($request->status)) {
            $model = $model->where('payments.status', '=', $request->status);
        }

        if ($request->project != 'all' && !is_null($request->project)) {
            $model = $model->where('payments.project_id', '=', $request->project);
        }

        if ($request->client != 'all' && !is_null($request->client)) {
            $model = $model->where('projects.client_id', '=', $request->client);
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
            ->setTableId('payments-table')
            ->columns($this->processTitle($this->getColumns()))
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
                   window.LaravelDataTables["payments-table"].buttons().container()
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
            __('app.invoice') . '#' => ['data' => 'invoice_number', 'name' => 'invoice.invoice_number'],
            __('modules.invoices.amount') => ['data' => 'amount', 'name' => 'amount'],
            __('modules.payments.paidOn') => ['data' => 'paid_on', 'name' => 'paid_on'],
            __('app.status') => ['data' => 'status', 'name' => 'status'],
            __('app.remark') => ['data' => 'remarks', 'name' => 'remarks'],
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
                [__('app.project')  => ['data' => 'project_id', 'name' => 'project.project_name']] +
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
        return 'Payments_' . date('YmdHis');
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

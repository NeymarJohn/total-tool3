<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\Estimate;
use App\Task;
use App\TaskboardColumn;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class EstimatesDataTable extends BaseDataTable
{
    protected $firstEstimate;
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $firstEstimate = $this->firstEstimate;
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($firstEstimate) {
                $action = '<div class="btn-group dropdown m-r-10">
                <button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle waves-effect waves-light" type="button"><i class="fa fa-gears "></i></button>
                <ul role="menu" class="dropdown-menu">';
                if ($row->status != 'draft') {
                    $action .= '<li><a href="' . route("admin.estimates.download", $row->id) . '" ><i class="fa fa-download"></i> ' . __('app.download') . '</a></li>';
                }
                $action .= '<li><a href="' . route("front.estimate.show", md5($row->id)) . '" target="_blank"><i class="fa fa-eye"></i> ' . __('app.view') . '</a></li>';

                $action .= '<li><a href="javascript:;" data-toggle="tooltip"  data-estimate-id="' . $row->id . '" class="sendButton"><i class="fa fa-send"></i> ' . __('app.send') . '</a></li>';

                if ($row->status == 'waiting' || $row->status == 'draft') {
                    $action .= '<li><a href="' . route("admin.estimates.edit", $row->id) . '" ><i class="fa fa-pencil"></i> ' . __('app.edit') . '</a></li>';
                }
                 if ($firstEstimate->id == $row->id) {
                    $action .= '<li><a class="sa-params" href="javascript:;" data-estimate-id="' . $row->id . '"><i class="fa fa-times"></i> ' . __('app.delete') . '</a></li>';
                 }
                if ($row->status == 'waiting') {
                    $action .= '<li><a href="' . route("admin.all-invoices.convert-estimate", $row->id) . '" ><i class="ti-receipt"></i> ' . __('app.create') . ' ' . __('app.invoice') . '</a></li>';

                    $action .= '<li><a href="javascript:;" class="change-status" data-estimate-id="' . $row->id . '" ><i class="ti-times"></i> ' . __('app.cancelEstimate') . '</a></li>';
                }
                $action .= '<li><a href="' . route("admin.estimates.duplicate-estimate", $row->id) . '" ><i class="ti-files"></i> ' . __('app.create') . ' ' . __('app.duplicate') . '</a></li>';

                $action .= '</ul>
              </div>
              ';
                return $action;
            })
            ->addColumn('original_estimate_number', function ($row) {
                return '<a href="' . route("front.estimate.show", md5($row->id)) . '" target="_blank">' . $row->original_estimate_number . '</a>';
            })
            ->editColumn('name', function ($row) {
                return '<a href="' . route('admin.clients.show', $row->client_id) . '">' . ucwords($row->name) . '</a>';
            })
            ->editColumn('status', function ($row) {
                $status = '';
                if ($row->status == 'waiting') {
                    $status .= '<label class="label label-warning">' . strtoupper($row->status) . '</label>';
                } else if ($row->status == 'draft') {
                    $status .= '<label class="label label-primary">' . strtoupper($row->status) . '</label>';
                }else if ($row->status == 'canceled') {
                    $status .= '<label class="label label-info">' . strtoupper($row->status) . '</label>';
                } else if ($row->status == 'declined') {
                    $status .= '<label class="label label-danger">' . strtoupper($row->status) . '</label>';
                } else {
                    $status .= '<label class="label label-success">' . strtoupper($row->status) . '</label>';
                }

                if (!$row->send_status && $row->status != 'draft' && $row->status != 'canceled') {
                    $status .= '<br><br><label class="label label-inverse">' . strtoupper(__('modules.invoices.notSent')) . '</label>';
                }
                return $status;
            })
            ->editColumn('total', function ($row) {
                return $row->currency_symbol . $row->total;
            })
            ->editColumn(
                'valid_till',
                function ($row) {
                    return Carbon::parse($row->valid_till)->format($this->global->date_format);
                }
            )
            ->rawColumns(['name', 'action', 'status', 'original_estimate_number'])
            ->removeColumn('currency_symbol')
            ->removeColumn('client_id');
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

        $this->firstEstimate = Estimate::latest()->first();
        $model = Estimate::join('client_details', 'estimates.client_id', '=', 'client_details.user_id')
            ->join('currencies', 'currencies.id', '=', 'estimates.currency_id')
            ->join('users', 'users.id', '=', 'estimates.client_id')
            ->select('estimates.id', 'estimates.client_id', 'users.name', 'estimates.total', 'currencies.currency_symbol', 'estimates.status', 'estimates.valid_till', 'estimates.estimate_number', 'estimates.send_status');

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $model = $model->where(DB::raw('DATE(estimates.`valid_till`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $model = $model->where(DB::raw('DATE(estimates.`valid_till`)'), '<=', $endDate);
        }

        if ($request->status != 'all' && !is_null($request->status)) {
            $model = $model->where('estimates.status', '=', $request->status);
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
            ->setTableId('estimates-table')
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
                   window.LaravelDataTables["estimates-table"].buttons().container()
                    .appendTo( ".bg-title .text-right")
                }',
                'fnDrawCallback' => 'function( oSettings ) {
                    $("body").tooltip({
                        selector: \'[data-toggle="tooltip"]\'
                    })
                }',
                'order' => [
                    1, 
                    'desc'],
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
            __('app.id') => ['data' => 'id', 'name' => 'id', 'visible' => false],
            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
            __('app.estimate') . '#' => ['data' => 'original_estimate_number', 'name' => 'original_estimate_number'],
            __('app.client')  => ['data' => 'name', 'name' => 'users.name'],
            __('modules.invoices.total') => ['data' => 'total', 'name' => 'total'],
            __('modules.estimates.validTill') => ['data' => 'valid_till', 'name' => 'valid_till'],
            __('app.status') => ['data' => 'status', 'name' => 'status'],
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
        return 'estimates_' . date('YmdHis');
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

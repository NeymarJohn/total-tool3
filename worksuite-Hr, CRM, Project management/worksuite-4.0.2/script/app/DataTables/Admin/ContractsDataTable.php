<?php

namespace App\DataTables\Admin;

use App\Contract;
use App\DataTables\BaseDataTable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class ContractsDataTable extends BaseDataTable
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
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $action = '<div class="btn-group dropdown m-r-10">
                <button aria-expanded="false" data-toggle="dropdown" class="btn btn-default dropdown-toggle waves-effect waves-light" type="button"><i class="fa fa-gears "></i></button>
                <ul role="menu" class="dropdown-menu pull-right">
                  <li><a href="' . route('admin.contracts.show', md5($row->id)) . '" class="view-contact" data-contract-id="' . $row->id . '"><i class="fa fa-eye" aria-hidden="true"></i> ' . trans('app.view') . '</a></li>
                  <li><a href="' . route('admin.contracts.edit', [$row->id]) . '"><i class="fa fa-pencil" aria-hidden="true"></i> ' . trans('app.edit') . '</a></li>
                  <li><a href="' . route('admin.contracts.copy', [$row->id]) . '" data-contract-id="' . $row->id . '"><i class="fa fa-copy" aria-hidden="true"></i> ' . __('app.copy') . ' ' . __('app.menu.contract') . '</a></li>
                  <li><a href="javascript:;"   data-contract-id="' . $row->id . '"  class="sa-params"><i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '</a></li>';

                $action .= '</ul> </div>';

                return $action;
            })
            ->editColumn('start_date', function ($row) {
                return $row->start_date->format($this->global->date_format);

            })
            ->editColumn('end_date', function ($row) {
                if (is_null($row->end_date)) {
                    return "--";
                }
                return $row->end_date == null ? $row->end_date:$row->end_date->format($this->global->date_format);
            })
            ->editColumn('amount', function ($row) {
                return $this->global->currency->currency_symbol . $row->amount;
            })
            ->editColumn('client.name', function ($row) {
                return '<a href="' . route('admin.clients.show', $row->client_id) . '">' . ucfirst($row->client->name) . '</a>';
            })
            ->editColumn('signature', function ($row) {
                if ($row->signature) {
                    return 'signed';
                }
                return 'Not Signed';
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'client.name']);

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Contract $model)
    {
        $request = $this->request();

        $model = $model->with('contract_type', 'client', 'signature');

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $model = $model->where(DB::raw('DATE(contracts.`start_date`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $model = $model->where(DB::raw('DATE(contracts.`end_date`)'), '<=', $startDate);
        }

        if ($request->clientID != 'all' && !is_null($request->clientID)) {
            $model = $model->where('contracts.client_id', '=', $request->clientID);
        }

        if ($request->contractType != 'all' && !is_null($request->contractType)) {
            $model = $model->where('contracts.contract_type_id', '=', $request->contractType);
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
            ->setTableId('contracts-table')
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
                   window.LaravelDataTables["contracts-table"].buttons().container()
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
            __('app.id') => ['data' => 'id', 'name' => 'id', 'visible' => false, 'exportable' => false],
            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
            __('app.subject') => ['data' => 'subject', 'name' => 'subject'],
            __('app.client')  => ['data' => 'client.name', 'name' => 'client.name'],
            __('app.amount') => ['data' => 'amount', 'name' => 'amount'],
            __('app.startDate') => ['data' => 'start_date', 'name' => 'start_date'],
            __('app.endDate') => ['data' => 'end_date', 'name' => 'end_date'],
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
        return 'Contracts_' . date('YmdHis');
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

<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\Proposal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class ProposalDataTable extends BaseDataTable
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
                $convert = '';

                if ($row->status == 'waiting') {
                    $convert = '<li><a href="' . route("admin.proposals.convert-proposal", $row->id) . '" ><i class="fa fa-file"></i> Convert Invoice </a></li>
                                <li><a href="' . route("admin.proposals.edit", $row->id) . '" ><i class="fa fa-pencil"></i> ' . __('modules.proposal.edit') . ' </a></li>';
                }
                return '<div class="btn-group m-r-10">
                <button aria-expanded="false" data-toggle="dropdown" class="btn btn-info btn-outline  dropdown-toggle waves-effect waves-light" type="button">' . __('modules.proposal.action') . ' <span class="caret"></span></button>
                <ul role="menu" class="dropdown-menu">
                  <li><a href="' . route("admin.proposals.download", $row->id) . '" ><i class="fa fa-download"></i> ' . __('modules.proposal.download') . '</a></li>
                  <li><a target="_blank" href="' . route("front.proposal", md5($row->id)) . '" ><i class="fa fa-link"></i> ' . __('modules.proposal.publicLink') . '</a></li>
                  <li><a href="' . route("admin.proposals.detail", $row->id) . '" ><i class="fa fa-search"></i> ' . __('app.view') . ' </a></li>
                  ' . $convert . '
                  <li><a class="sa-params" href="javascript:;" data-proposal-id="' . $row->id . '"><i class="fa fa-times"></i> ' . __('modules.proposal.delete') . ' </a></li>
                  <li><a class="send-mail" href="javascript:;" data-proposal-id="' . $row->id . '"  ><i class="fa fa-mail-reply"></i> ' . __('modules.proposal.sendProposal') . ' </a></li>
                </ul>
              </div>';
            })
            ->editColumn('client_name', function ($row) {
                if ($row->client_id) {
                    return '<a href="' . route('admin.clients.show', $row->client_id) . '">' . ucwords($row->client_name) . '</a>';
                }
                return ucwords($row->client_name);
            })
            ->editColumn('status', function ($row) {
                if ($row->status == 'waiting') {
                    return '<label class="label label-warning">' . strtoupper($row->status) . '</label>';
                }
                if ($row->status == 'declined') {
                    return '<label class="label label-danger">' . strtoupper($row->status) . '</label>';
                } else {
                    return '<label class="label label-success">' . strtoupper($row->status) . '</label>';
                }
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
            ->addIndexColumn()
            ->rawColumns(['name', 'action', 'status'])
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
        $model = Proposal::select('proposals.id', 'leads.client_name', 'leads.client_id', 'total', 'valid_till', 'status', 'currencies.currency_symbol', 'leads.company_name')
            ->join('currencies', 'currencies.id', '=', 'proposals.currency_id')
            ->join('leads', 'leads.id', 'proposals.lead_id');

        if ($request->startDate !== null && $request->startDate != 'null' && $request->startDate != '') {
            $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate)->toDateString();
            $model = $model->where(DB::raw('DATE(proposals.`valid_till`)'), '>=', $startDate);
        }

        if ($request->endDate !== null && $request->endDate != 'null' && $request->endDate != '') {
            $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate)->toDateString();
            $model = $model->where(DB::raw('DATE(proposals.`valid_till`)'), '<=', $endDate);
        }
        if ($request->leadId !== null && $request->leadId != 'null' && $request->leadId != '') {
            $model = $model->where('proposals.lead_id', $request->leadId);
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
            ->setTableId('invoices-table')
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
                   window.LaravelDataTables["invoices-table"].buttons().container()
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
            __('app.id') => ['data' => 'id', 'name' => 'id', 'visible' => false],
            '#' => ['data' => 'DT_RowIndex', 'orderable' => false, 'searchable' => false],
            __('app.lead') . '#' => ['data' => 'client_name', 'name' => 'leads.client_name'],
            __('modules.invoices.total')  => ['data' => 'total', 'name' => 'total'],
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
        return 'Invoices_' . date('YmdHis');
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

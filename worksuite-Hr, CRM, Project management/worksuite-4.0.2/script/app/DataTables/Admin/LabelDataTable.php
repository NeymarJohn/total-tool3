<?php

namespace App\DataTables\Admin;


use App\DataTables\BaseDataTable;
use App\TaskLabelList;
use Yajra\DataTables\Html\Column;

class LabelDataTable extends BaseDataTable
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
                  <li><a href="' . route('admin.task-label.edit', [$row->id]) . '"><i class="fa fa-pencil" aria-hidden="true"></i> ' . trans('app.edit') . '</a></li>
                  <li><a href="javascript:;"   data-contract-id="' . $row->id . '"  class="sa-params"><i class="fa fa-times" aria-hidden="true"></i> ' . trans('app.delete') . '</a></li>';

                $action .= '</ul> </div>';

                return $action;
            })
            ->editColumn('label_name', function ($row) {

                if ($row->color) {
                    return '<label class="badge" style="background: ' . $row->color . ';">' . ucwords($row->label_name) . '</label>';
                }
                return '<label class="badge"  style="background:#3b0ae1;">' . ucwords($row->label_name) . '</label>';
            })
            ->editColumn('description', function ($row) {
                if ($row->description) {
                    return ucwords($row->description);
                }
                return '--';
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'label_name', 'color']);
    }

    /**
     * @param TaskLabelList $model
     * @return TaskLabelList
     */
    public function query(TaskLabelList $model)
    {
        $request = $this->request();

        return $model->select('id', 'label_name', 'color', 'description');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('taskLabelList-table')
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
            //            ->buttons(
            //                Button::make(['extend' => 'export', 'buttons' => ['excel', 'csv'], 'text' => '<i class="fa fa-download"></i> ' . trans('app.exportExcel') . '&nbsp;<span class="caret"></span>'])
            //            )
            ->parameters([
                'initComplete' => 'function () {
                   window.LaravelDataTables["taskLabelList-table"].buttons().container()
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
            '#' => ['data' => 'id', 'name' => 'id', 'visible' => true],
            //            __('app.id') => ['data' => 'id', 'name' => 'id', 'visible' => false],
            __('app.labelName') => ['data' => 'label_name', 'name' => 'label_name'],
            //            __('app.color')  => ['data' => 'color', 'name' => 'color'],
            __('app.description') => ['data' => 'description', 'name' => 'description'],
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
        return 'taskLabelList_' . date('YmdHis');
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

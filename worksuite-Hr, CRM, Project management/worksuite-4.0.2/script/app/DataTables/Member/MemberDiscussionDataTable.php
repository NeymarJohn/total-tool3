<?php

namespace App\DataTables\Member;

use App\Contract;
use App\DataTables\BaseDataTable;
use App\Discussion;
use App\Payment;
use App\Product;
use App\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class MemberDiscussionDataTable extends BaseDataTable
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
            ->editColumn('title', function ($row) {
                $title = '<div class="row">';

                $title.= '<div class="col-xs-1">';
                $title.= '<img src="'.$row->user->image_url.'" class="img-circle" width="35" height="35" />';
                $title.= '</div>';

                $title.= '<div class="col-xs-8">';
                $title.= '<h5 class="font-semi-bold p-t-0 m-t-0">';
                
                if (!is_null($row->project_id)) {
                    $title.= '<a href="'.route('member.projects.discussionReplies', [$row->project_id, $row->id]).'" class="text-dark">' . ucwords($row->title) . '</a>';
                } else {
                    $title.= '<a href="'.route('member.discussion.show', [$row->id]).'" class="text-dark">' . ucwords($row->title) . '</a>';
                }

                $title.= '</h5>';
                
                if (!is_null($row->last_reply_by_id)) {
                    $title.= ucwords($row->last_reply_by->name).' ';
                }

                if (count($row->replies) > 1) {
                    $title.= __('modules.discussions.replied');
                } else {
                    $title.= __('modules.discussions.posted');
                }

                $title.= ' <span class="text-muted font-semi-bold">'.$row->last_reply_at->timezone(global_setting()->timezone)->format(global_setting()->date_format.' '.global_setting()->time_format).'</span>';

                $title.= '</div>';

                $title.= '<div class="col-xs-1">';
                $title.= '<span class="font-semi-bold font-medium"><i class="fa fa-comment"></i> '.count($row->replies).'</span>';
                $title.= '</div>';

                $title.= '<div class="col-xs-2 text-right">';
                $title.= '<span style="color: '. $row->category->color .'"><i class="fa fa-circle"></i> '. ucwords($row->category->name).'</span>';
                $title.= '</div>';

                $title.= '</div>';   
                return $title;             
            })
            ->rawColumns(['title']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Discussion $model)
    {
        $request = $this->request();

        $model = $model->with('user', 'replies', 'category', 'last_reply_by')
            ->select('discussions.*');

        if (!is_null($this->project_id)) {
            $model->where('project_id', $this->project_id);
        }

        if (!is_null($request->category_id)) {
            $model->where('discussion_category_id', $request->category_id);
        }
        $model->orderBy('id', 'desc');

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
            ->setTableId('projects-table')
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
                   window.LaravelDataTables["projects-table"].buttons().container()
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
            __('app.title') => ['data' => 'title', 'name' => 'title']
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Discussions_' . date('YmdHis');
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

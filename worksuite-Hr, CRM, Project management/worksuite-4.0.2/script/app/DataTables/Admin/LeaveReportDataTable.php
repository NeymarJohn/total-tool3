<?php

namespace App\DataTables\Admin;

use App\DataTables\BaseDataTable;
use App\Estimate;
use App\Expense;
use App\Invoice;
use App\Notice;
use App\Payment;
use App\Task;
use App\TaskboardColumn;
use App\Ticket;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class LeaveReportDataTable extends BaseDataTable
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
            ->addColumn('name', function ($row) {
                return ucwords($row->name);
            })
            ->addColumn('approvedLeave', function ($row) {
                return ($row->count_approved_leaves + ($row->count_approved_half_leaves)/2) == 0 ? '0' : ($row->count_approved_leaves + ($row->count_approved_half_leaves)/2);
            })
            ->addColumn('approve', function ($row) {
                return '<div class="label-success label">' . ($row->count_approved_leaves + ($row->count_approved_half_leaves)/2) . '</div>
                <a href="javascript:;" class="view-approve" data-pk="' . $row->id . '">View</a>';
            })
            ->addColumn('pendingLeave', function ($row) {
                return ($row->count_pending_leaves + ($row->count_pending_half_leaves)/2) == 0 ? '0' : ($row->count_pending_leaves + ($row->count_pending_half_leaves)/2);
            })
            ->addColumn('pending', function ($row) {
                return '<div class="label-warning label">' . ($row->count_pending_leaves + ($row->count_pending_half_leaves)/2) . '</div>
                <a href="javascript:;" data-pk="' . $row->id . '" class="view-pending">View</a>';
            })
            ->addColumn('upcoming', function ($row) {
                return '<div class="label-info label">' . ($row->count_upcoming_leaves + ($row->count_upcoming_half_leaves)/2) . '</div>
                <a href="javascript:;" data-pk="' . $row->id . '" class="view-upcoming">View</a>';
            })
            ->addColumn('upcomingLeave', function ($row) {
                return ($row->count_upcoming_leaves + ($row->count_upcoming_half_leaves)/2) == 0 ? '0' : ($row->count_upcoming_leaves + ($row->count_upcoming_half_leaves)/2);
            })
            ->addColumn('action', function ($row) {
                return '<a  href="javascript:;" data-pk="' . $row->id . '"  class="btn btn-info btn-outline btn-sm exportUserData"
                      data-toggle="tooltip" data-original-title="Export to excel"><i class="ti-export" aria-hidden="true"></i> Export</a>';
            })
            ->addIndexColumn()
            ->rawColumns(['approve', 'upcoming', 'pending', 'action']);

    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Product $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        $request = $this->request();
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $employeeId = $request->employeeId;

        $startDate = Carbon::createFromFormat($this->global->date_format, $startDate)->toDateString();
        $endDate = Carbon::createFromFormat($this->global->date_format, $endDate)->toDateString();

        $startDt = '';
        $endDt = '';

        if (!is_null($startDate)) {
            $startDt = 'and DATE(leaves.`leave_date`) >= ' . '"' . $startDate . '"';
        }

        if (!is_null($endDate)) {
            $endDt = 'and DATE(leaves.`leave_date`) <= ' . '"' . $endDate . '"';
        }

        $leavesList = User::selectRaw(
            'users.id, users.name, 
                ( select count("id") from leaves where user_id = users.id and leaves.duration != \'half day\' and leaves.status = \'approved\' ' . $startDt . ' ' . $endDt . ' ) as count_approved_leaves,
                ( select count("id") from leaves where user_id = users.id and leaves.duration = \'half day\' and leaves.status = \'approved\' ' . $startDt . ' ' . $endDt . ' ) as count_approved_half_leaves,
                ( select count("id") from leaves where user_id = users.id and leaves.duration != \'half day\' and leaves.status = \'pending\' ' . $startDt . ' ' . $endDt . ') as count_pending_leaves, 
                ( select count("id") from leaves where user_id = users.id and leaves.duration = \'half day\' and leaves.status = \'pending\' ' . $startDt . ' ' . $endDt . ') as count_pending_half_leaves, 
                ( select count("id") from leaves where user_id = users.id and leaves.duration != \'half day\' and leaves.leave_date > "' . Carbon::now()->format('Y-m-d') . '" and leaves.status != \'rejected\' ' . $startDt . ' ' . $endDt . ') as count_upcoming_leaves,
                ( select count("id") from leaves where user_id = users.id and leaves.duration = \'half day\' and leaves.leave_date > "' . Carbon::now()->format('Y-m-d') . '" and leaves.status != \'rejected\' ' . $startDt . ' ' . $endDt . ') as count_upcoming_half_leaves'
        )->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', '<>', 'client');

        if ($employeeId != 0) {
            $leavesList->where('users.id', $employeeId);
        }

        $leaves = $leavesList->groupBy('users.id');

        return $leaves;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('leave-report-table')
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
                   window.LaravelDataTables["leave-report-table"].buttons().container()
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
            __('app.id') => ['data' => 'id', 'name' => 'id', 'visible' => false , 'exportable' => false],
            ' #' => ['data' => 'DT_RowIndex', 'orderable' =>false, 'searchable' => false ],
            __('app.employee')  => ['data' => 'name', 'name' => 'users.name'],
            __('app.approved').' '.__('app.menu.leaves') => ['data' => 'approve', 'name' => 'approve', 'exportable' => false],
            __('app.approved') => ['data' => 'approvedLeave', 'name' => 'approvedLeave','visible' => false],
            __('app.pending').' '.__('app.menu.leaves') => ['data' => 'pending', 'name' => 'pending', 'exportable' => false],
            __('app.pending') => ['data' => 'pendingLeave', 'name' => 'pendingLeave', 'visible' => false],
            __('app.upcoming').' '.__('app.menu.leaves') => ['data' => 'upcoming', 'name' => 'upcoming', 'exportable' => false],
            __('app.upcoming') => ['data' => 'upcomingLeave', 'name' => 'upcomingLeave', 'visible' => false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Leave report_' . date('YmdHis');
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

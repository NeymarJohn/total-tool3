<?php

namespace App\DataTables\Admin;

use App\Attendance;
use App\AttendanceSetting;
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
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;

class AttendanceReportDataTable extends BaseDataTable
{

    /**
     * @param $query
     * @return \Yajra\DataTables\CollectionDataTable|\Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->collection($this->query())
            ->addIndexColumn();

    }

    /**
     * @param User $model
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        set_time_limit(0);
        $request = $this->request();
        $allEmployees = $this->employees = User::allEmployees();
        if ($request->employee != 'all') {
            $allEmployees = User::where('id', $request->employee)->get();
        }

        $this->attendanceSettings = AttendanceSetting::first();
        $openDays = json_decode($this->attendanceSettings->office_open_days);
        $this->startDate = $startDate = Carbon::createFromFormat($this->global->date_format, $request->startDate);
        $this->endDate = $endDate = Carbon::createFromFormat($this->global->date_format, $request->endDate);
        $period = CarbonPeriod::create($this->startDate,  $this->endDate);

        $this->totalDays = $totalWorkingDays = $startDate->diffInDaysFiltered(function (Carbon $date) use ($openDays) {
            foreach ($openDays as $day) {
                if ($date->dayOfWeek == $day) {
                    return $date;
                }
            }
        }, $endDate);

        $summaryData = array();

        foreach ($allEmployees as $key => $employee) {

            $summaryData[$key]['user_id'] = $employee->id;
            $summaryData[$key]['name'] = $employee->name;

            $timeLogInMinutes = 0;
            foreach ($period as $date) {
                $attendanceDate = $date->toDateString();
                $clockins = Attendance::select('clock_in_time', 'clock_out_time')
                    ->where(DB::raw('DATE(attendances.clock_in_time)'), $attendanceDate)
                    ->where('user_id', $employee->id)->orderBy('id', 'asc')->get();
                $this->firstClockIn = $clockins->first();
                $this->lastClockOut = $clockins->last();

                if (!is_null($this->firstClockIn)) {

                    $this->startTime = Carbon::parse($this->firstClockIn->clock_in_time)->timezone($this->global->timezone);

                    if (!is_null($this->lastClockOut->clock_out_time)) {
                        $this->endTime = Carbon::parse($this->lastClockOut->clock_out_time)->timezone($this->global->timezone);
                    } elseif (($this->lastClockOut->clock_in_time->timezone($this->global->timezone)->format('Y-m-d') != Carbon::now()->timezone($this->global->timezone)->format('Y-m-d')) && is_null($this->lastClockOut->clock_out_time)) {
                        $this->endTime = Carbon::parse($this->startTime->format('Y-m-d') . ' ' . $this->attendanceSettings->office_end_time, $this->global->timezone);
                        $this->notClockedOut = true;
                    } else {
                        $this->notClockedOut = true;
                        $this->endTime = Carbon::now()->timezone($this->global->timezone);
                    }

                    $timeLogInMinutes = $timeLogInMinutes + $this->endTime->diffInMinutes($this->startTime, true);
                }
            }
            $timeLog = intdiv($timeLogInMinutes, 60) . ' hrs ';

            if (($timeLogInMinutes % 60) > 0) {
                $timeLog .= ($timeLogInMinutes % 60) . ' mins';
            }

            $daysPresent = Attendance::countDaysPresentByUser($this->startDate, $this->endDate, $employee->id);
            $lateDayCount = Attendance::countDaysLateByUser($this->startDate, $this->endDate, $employee->id);
            $halfDayCount = Attendance::countHalfDaysByUser($this->startDate, $this->endDate, $employee->id);
            $absentDays = (($totalWorkingDays - $daysPresent) < 0) ? '0' : ($totalWorkingDays - $daysPresent);

            $summaryData[$key]['present_days'] = $daysPresent == 0 ? '0' : $daysPresent;
            $summaryData[$key]['absent_days'] = $absentDays == 0 ? '0' : $absentDays;
            $summaryData[$key]['half_day_count'] = $halfDayCount == 0 ? '0' : $halfDayCount;
            $summaryData[$key]['late_day_count'] = $lateDayCount == 0 ? '0' : $lateDayCount;
            $summaryData[$key]['hours_clocked'] = $timeLog;
        }

        return collect($summaryData);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('attendance-report-table')
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
                   window.LaravelDataTables["attendance-report-table"].buttons().container()
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
            ' #' => ['data' => 'DT_RowIndex', 'orderable' =>false, 'searchable' => false ],
            __('app.employee')  => ['data' => 'name', 'name' => 'users.name'],
            __('modules.attendance.present') => ['data' => 'present_days', 'name' => 'present_days'],
            __('modules.attendance.absent') => ['data' => 'absent_days', 'name' => 'absent_days'],
            __('modules.attendance.hoursClocked') => ['data' => 'hours_clocked', 'name' => 'hours_clocked'],
            __('app.days').' '.__('modules.attendance.late') => ['data' => 'late_day_count', 'name' => 'late_day_count'],
            __('modules.attendance.halfDay') => ['data' => 'half_day_count', 'name' => 'half_day_count'],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Attendance_report_' . date('YmdHis');
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

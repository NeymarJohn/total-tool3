<?php

namespace App\Http\Controllers\Admin;

use App\Attendance;
use App\AttendanceSetting;
use App\DataTables\Admin\AttendanceReportDataTable;
use App\Helper\Reply;
use App\User;
use Carbon\Carbon as Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceReportController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.attendanceReport';
        $this->pageIcon = 'icon-clock';
    }

    public function index(AttendanceReportDataTable $dataTable)
    {
        $this->employees = User::allEmployees();
        return $dataTable->render('admin.reports.attendance.index', $this->data);
    }

    public function report(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $employee = $request->employee_id;
        $this->attendanceSettings = AttendanceSetting::first();
        $openDays = json_decode($this->attendanceSettings->office_open_days);
        $this->startDate = $startDate = Carbon::createFromFormat($this->global->date_format, $startDate);
        $this->endDate = $endDate = Carbon::createFromFormat($this->global->date_format, $endDate);

        $this->totalDays = $totalWorkingDays = $startDate->diffInDaysFiltered(function (Carbon $date) use ($openDays) {
            foreach ($openDays as $day) {
                if ($date->dayOfWeek == $day) {
                    return $date;
                }
            }
        }, $endDate);

        return Reply::dataOnly(['status' => 'success', 'data' => $this->totalDays]);

    }

    public function reportExport($startDate = null, $endDate = null, $employee = null)
    {
        $allEmployees = $this->employees = User::allEmployees();
        if ($employee != 'all') {
            $allEmployees = User::where('id', $employee)->get();
        }

        $this->attendanceSettings = AttendanceSetting::first();
        $openDays = json_decode($this->attendanceSettings->office_open_days);
        $this->startDate = Carbon::createFromFormat($this->global->date_format, $startDate);
        $this->endDate = Carbon::createFromFormat($this->global->date_format, $endDate);
        $period = CarbonPeriod::create($this->startDate,  $this->endDate);


        $this->totalDays = $totalWorkingDays = $this->startDate->diffInDaysFiltered(function (Carbon $date) use ($openDays) {
            foreach ($openDays as $day) {
                if ($date->dayOfWeek == $day) {
                    return $date;
                }
            }
        }, $this->endDate);
        
        $summaryData = array();

        foreach ($allEmployees as $key=>$employee) {

            $summaryData[$key]['user_id'] = ($key+1);
            $summaryData[$key]['name'] = $employee->name;
      
            $timeLogInMinutes = 0;
            foreach ($period as $date) {
                $attendanceDate = $date->toDateString();
                $this->firstClockIn = Attendance::where(DB::raw('DATE(attendances.clock_in_time)'), $attendanceDate)
                    ->where('user_id', $employee->id)->orderBy('id', 'asc')->first();

                if (!is_null($this->firstClockIn)) {
                    $this->lastClockOut = Attendance::where(DB::raw('DATE(attendances.clock_in_time)'), $attendanceDate)
                        ->where('user_id', $employee->id)->orderBy('id', 'desc')->first();

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

            $summaryData[$key]['present_days'] = $daysPresent;
            $summaryData[$key]['absent_days'] = $absentDays;
            $summaryData[$key]['hours_clocked'] = $timeLog;
            $summaryData[$key]['late_day_count'] = $lateDayCount;
            $summaryData[$key]['half_day_count'] = $halfDayCount;
        }

        // Initialize the array which will be passed into the Excel
        // generator.
        $exportArray = [];

        // Define the Excel spreadsheet headers
        $exportArray[] = ['#', __('app.employee'), __('modules.attendance.present'), __('modules.attendance.absent'), __('modules.attendance.hoursClocked'), __('app.days').' '.__('modules.attendance.late'), __('modules.attendance.halfDay')];

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($summaryData as $row) {
            $exportArray[] = $row;
        }

        // Generate and return the spreadsheet
        Excel::create(__('app.menu.attendanceReport'), function ($excel) use ($exportArray, $startDate, $endDate) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle(__('app.menu.attendanceReport'));
            $excel->setCreator('Worksuite')->setCompany($this->companyName);
            $excel->setDescription(__('app.menu.attendanceReport'));

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($exportArray, $startDate, $endDate) {
                $sheet->row(1, array(
                    __('app.startDate'), __('app.endDate'), __('modules.attendance.totalWorkingDays')
                ));

                // Manipulate 2nd row
                $sheet->row(2, array(
                    $startDate, $endDate, $this->totalDays
                ));
                $sheet->fromArray($exportArray, null, 'A4', false, false);

                $sheet->row(1, function ($row) {

                    // call row manipulation methods
                    $row->setFont(array(
                        'bold' => true
                    ));
                });

                $sheet->row(4, function ($row) {

                    // call row manipulation methods
                    $row->setFont(array(
                        'bold' => true
                    ));
                });

            });


        })->download('xlsx');

    }
}

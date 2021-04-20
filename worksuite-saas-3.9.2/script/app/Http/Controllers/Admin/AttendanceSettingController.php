<?php

namespace App\Http\Controllers\Admin;

use App\AttendanceSetting;
use App\Helper\Reply;
use App\Http\Requests\AttendanceSetting\UpdateAttendanceSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceSettingController extends AdminBaseController
{

    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.attendanceSettings';
        $this->pageIcon = 'icon-settings';
        $this->middleware(function ($request, $next) {
            if(!in_array('attendance',$this->user->modules)){
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   $this->ipAddresses = [];
        $this->attendanceSetting = AttendanceSetting::first();
        $this->openDays = json_decode($this->attendanceSetting->office_open_days);
        if (json_decode($this->attendanceSetting->ip_address)) {
            $this->ipAddresses = json_decode($this->attendanceSetting->ip_address, true);
        }
        return view('admin.attendance-settings.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAttendanceSetting $request, $id)
    {
        $setting = AttendanceSetting::where('company_id', company()->id)->first();
        $setting->office_start_time = Carbon::createFromFormat($this->global->time_format, $request->office_start_time);
        $setting->office_end_time = Carbon::createFromFormat($this->global->time_format, $request->office_end_time);
        $setting->halfday_mark_time = Carbon::createFromFormat($this->global->time_format, $request->halfday_mark_time);
        $setting->late_mark_duration = $request->late_mark_duration;
        $setting->clockin_in_day = $request->clockin_in_day;
        ($request->employee_clock_in_out == 'yes') ? $setting->employee_clock_in_out = 'yes' : $setting->employee_clock_in_out = 'no';
        $setting->office_open_days = json_encode($request->office_open_days);
        ($request->radius_check == 'yes') ? $setting->radius_check = 'yes' : $setting->radius_check = 'no';
        ($request->ip_check == 'yes') ? $setting->ip_check = 'yes' : $setting->ip_check = 'no';
        $setting->radius = $request->radius;
        $ip_address = [];
        if ($request->ip) {
            foreach ($request->ip as $key => $value)
                if (!empty($value))
                    $ip_address[] = $value;

        }
        $setting->ip_address = $ip_address ? json_encode($ip_address) : NULL;
        $setting->save();

        return Reply::success(__('messages.settingsUpdated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

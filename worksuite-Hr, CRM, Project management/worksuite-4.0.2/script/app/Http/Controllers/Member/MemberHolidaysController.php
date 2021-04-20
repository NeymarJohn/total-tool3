<?php
namespace App\Http\Controllers\Member;

use App\AttendanceSetting;
use App\Helper\Reply;
use App\Http\Requests\CommonRequest;
use App\Http\Requests\Holiday\CreateRequest;
use App\Http\Requests\Holiday\DeleteRequest;
use App\Http\Requests\Holiday\IndexRequest;
use App\Http\Requests\Holiday\UpdateRequest;
use App\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class MemberHolidaysController extends MemberBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'user-follow';
        $this->pageTitle = 'app.menu.holiday';
        $this->middleware(function ($request, $next) {
            if(!in_array('holidays',$this->user->modules)){
                abort(403);
            }
            return $next($request);
        });


        for ($m = 1; $m <= 12; $m++) {
            $month[] = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
        }

        $this->months = $month;
        $this->currentMonth = date('F');
    }

    public function index(IndexRequest $request)
    {
        $this->holidays = Holiday::orderBy('date', 'ASC')->get();;
        $this->holidayActive = 'active';
        $hol = [];

        $years = [];
        $lastFiveYear = (int)Carbon::now()->subYears(5)->format('Y');
        $nextYear = (int)Carbon::now()->addYear()->format('Y');

        for($i=$lastFiveYear;$i <= $nextYear;$i++ ){
            $years [] =$i;
        }
        $this->years = $years;
        $this->year = Carbon::now()->format('Y');
        $dateArr = $this->getDateForSpecificDayBetweenDates($this->year . '-01-01', $this->year . '-12-31', 0);
        $this->number_of_sundays = count($dateArr);

        $this->holidays_in_db = count($this->holidays);

        foreach ($this->holidays as $holiday) {
            $hol[date('F', strtotime($holiday->date))]['id'][] = $holiday->id;
            $hol[date('F', strtotime($holiday->date))]['date'][] = $holiday->date->format($this->global->date_format);
            $hol[date('F', strtotime($holiday->date))]['ocassion'][] = ($holiday->occassion)? $holiday->occassion : 'Not Define'; ;
            $hol[date('F', strtotime($holiday->date))]['day'][] = $holiday->date->format('D');
        }
        $this->holidaysArray = $hol;
        return View::make('member.holidays.index', $this->data);
    }

    public function viewHoliday($year)
    {

        $this->holidayActive = 'active';
        $hol = [];

        $this->holidays = Holiday::orderBy('date', 'ASC')
            ->where(DB::raw('Year(holidays.date)'), '=', $year)
            ->get();

        $dateArr = $this->getDateForSpecificDayBetweenDates($year . '-01-01', $year . '-12-31', 0);
        $this->number_of_sundays = count($dateArr);

        $this->holidays_in_db = count($this->holidays);

        foreach ($this->holidays as $holiday) {
            $hol[date('F', strtotime($holiday->date))]['id'][] = $holiday->id;
            $hol[date('F', strtotime($holiday->date))]['date'][] = $holiday->date->format($this->global->date_format);
            $hol[date('F', strtotime($holiday->date))]['ocassion'][] = ($holiday->occassion)? $holiday->occassion : 'Not Define';
            $hol[date('F', strtotime($holiday->date))]['day'][] = $holiday->date->format('D');
        }
        $this->holidaysArray = $hol;

        $view = View::make('member.holidays.holiday-view', $this->data)->render();
        return Reply::dataOnly(['view' =>$view, 'number_of_sundays' => $this->number_of_sundays, 'holidays_in_db' => $this->holidays_in_db]);

    }

    /**
     * Show the form for creating a new holiday
     *
     * @return Response
     */
    public function create()
    {
        if (!$this->user->can('add_holiday')) {
            abort(403);
        }

        return View::make('member.holidays.create', $this->data);
    }

    /**
     * Store a newly created holiday in storage.
     *
     * @return Response
     */
    public function store(CreateRequest $request)
    {
        if (!$this->user->can('add_holiday')) {
            abort(403);
        }

        $holiday = array_combine($request->date, $request->occasion);
        foreach ($holiday as $index => $value) {
            if ($index) {
                $add = Holiday::firstOrCreate([
                    'date' => Carbon::createFromFormat($this->global->date_format, $index)->format('Y-m-d'),
                    'occassion' => $value,
                ]);
            }
        }
        return Reply::redirect(route('member.holidays.index'), __('messages.holidayAddedSuccess'));
    }

    /**
     * Display the specified holiday.
     */
    public function show($id)
    {
        $this->holiday = Holiday::findOrFail($id);

        return view('member.holidays.show', $this->data);
    }

    /**
     * Show the form for editing the specified holiday.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        if(!$this->user->can('edit_holiday')){
            abort(403);
        }

        $this->holiday = Holiday::find($id);

        return view('member.holidays.edit', $this->data);
    }

    /**
     * Update the specified holiday in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(UpdateRequest $request, $id)
    {
        if(!$this->user->can('edit_holiday')){
            abort(403);
        }

        $holiday = Holiday::findOrFail($id);
        $data = $request->all();
        $holiday->update($data);

        return Redirect::route('member.holidays.index');
    }

    /**
     * Remove the specified holiday from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy(DeleteRequest $request, $id)
    {
        if(!$this->user->can('delete_holiday')){
            abort(403);
        }
        Holiday::destroy($id);
        return Reply::redirect(route('member.holidays.index'), __('messages.holidayDeletedSuccess'));
    }

    /**
     * @return array
     */

    public function Sunday()
    {
        $year = Carbon::now()->format('Y');

        $dateArr = $this->getDateForSpecificDayBetweenDates($year . '-01-01', $year . '-12-31', 0);

        foreach ($dateArr as $date) {
            Holiday::firstOrCreate([
                'date' => $date,
                'occassion' => 'Sunday'
            ]);
        }
        return Reply::redirect(route('member.holidays.index'), __('messages.holidayAddedSuccess'));
    }

    /**
     * @param $startDate
     * @param $endDate
     * @param $weekdayNumber
     * @return array
     */
    public function getDateForSpecificDayBetweenDates($startDate, $endDate, $weekdayNumber)
    {
        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);

        $dateArr = [];

        do {
            if (date('w', $startDate) != $weekdayNumber) {
                $startDate += (24 * 3600); // add 1 day
            }
        } while (date('w', $startDate) != $weekdayNumber);


        while ($startDate <= $endDate) {
            $dateArr[] = date('Y-m-d', $startDate);
            $startDate += (7 * 24 * 3600); // add 7 days
        }

        return ($dateArr);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function holidayCalendar(Request $request, $year = null){

        $this->pageTitle = 'Holiday Calendar';
        $this->year = Carbon::now()->format('Y');

        if($year){
            $this->year = $year;
        }

        $years = [];
        $lastFiveYear = (int)Carbon::now()->subYears(5)->format('Y');
        $nextYear = (int)Carbon::now()->addYear()->format('Y');

        for($i=$lastFiveYear;$i <= $nextYear;$i++ ){
            $years [] =$i;
        }
        $this->years = $years;

        $this->holidays = Holiday::where(DB::raw('Year(holidays.date)'), '=', $this->year)->get();

        if($request->ajax()){
            return Reply::dataOnly(['eventData' => $this->holidays, 'year' => $this->year]);
        }

        return view('member.holidays.holiday-calendar', $this->data);
    }

    public function markHoliday()
    {
        $this->days = [
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
            'Sunday'
        ];

        $attandanceSetting = AttendanceSetting::first();

        $this->holidays = $this->missing_number(json_decode($attandanceSetting->office_open_days));
        $holidaysArray = [];
        foreach($this->holidays as $index => $holiday){
            $holidaysArray[$holiday] = $this->days[$holiday-1];
        }
        $this->holidaysArray = $holidaysArray;

        return View::make('member.holidays.mark-holiday', $this->data);
    }

    public function missing_number($num_list)
    {
        // construct a new array
        $new_arr = range(1,7);
        return array_diff($new_arr, $num_list);
    }

    public function markDayHoliday(CommonRequest $request){

        if (!$request->has('office_holiday_days')) {
            return Reply::error(__('messages.checkDayHoliday'));
        }
        $year = Carbon::now()->format('Y');
        if($request->has('year')){
            $year = $request->has('year');
        }


        $daysss = [];
        $this->days = [
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'
        ];

        if($request->office_holiday_days != null && count($request->office_holiday_days) > 0){
            foreach($request->office_holiday_days as $holiday){
                $daysss[] = $this->days[($holiday-1)];
                $day = $holiday;
                if($holiday == 7){
                    $day = 0;
                }
                $dateArr = $this->getDateForSpecificDayBetweenDates($year . '-01-01', $year . '-12-31', ($day));

                foreach ($dateArr as $date) {
                    Holiday::firstOrCreate([
                        'date' => $date,
                        'occassion' => $this->days[$day]
                    ]);
                }
            }

        }
        return Reply::redirect(route('member.holidays.index'), __('messages.holidayAddedSuccess'));
    }
    /**
     * @param Request $request
     * @return mixed
     * @throws \Throwable
     */
    public function getCalendarMonth(Request $request){
        $month = Carbon::createFromFormat('Y-m-d', $request->startDate)->format('m');
        $year = Carbon::createFromFormat('Y-m-d', $request->startDate)->format('Y');
        $this->holidays = Holiday::whereMonth('date', '=', $month)
            ->whereYear('date', '=', $year)
            ->get();

        $view = view('admin.holidays.month-wise-holiday', $this->data)->render();
        return Reply::dataOnly(['data'=> $view]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Event;
use App\EventAttendee;
use App\Helper\Reply;
use App\Http\Requests\Events\StoreEvent;
use App\Http\Requests\Events\UpdateEvent;
use App\Notifications\EventInvite;
use App\User;
use App\EventCategory;
use App\EventType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class AdminEventCalendarController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.Events';
        $this->pageIcon = 'icon-calender';
        $this->middleware(function ($request, $next) {
            abort_if(!in_array('events', $this->user->modules),403);
            return $next($request);
        });
    }

    public function index()
    {
       
        $this->employees = User::allEmployees();
        $this->events = Event::all();
        $this->clients = User::allClients();
        $this->categories = EventCategory::all();
        $this->eventTypes = EventType::all();
        return view('admin.event-calendar.index', $this->data);
    }
    public function filterEvent(Request $request)
    {
        $eventFilter = Event::select('events.id', 'event_name','label_color','where','description','start_date_time',
            'end_date_time','repeat','repeat_every','repeat_cycles','repeat_type','created_at','updated_at');
            if (request()->has('employee') && $request->employee != 0) {   
                $eventFilter->whereHas('attendee', function ($query)use($request) {
                    return $query->where('user_id',  $request->employee);
                   
                });      
            }
            if (request()->has('client') && $request->client != 0) {   
                $eventFilter->whereHas('attendee', function ($query)use($request) {
                    return $query->where('user_id',  $request->client);
                   
                });
            }
            if (request()->has('category') && $request->category != 0) {   
                $eventFilter->whereHas('category', function ($query)use($request) {
                    return $query->where('category_id',  $request->category);
                   
                });
            }
            if (request()->has('event_type') && $request->event_type != 0) {   

                $eventFilter->whereHas('eventType', function ($query)use($request) {
                    return $query->where('event_type_id',  $request->event_type);
                   
                });
            }
            $eventFilter = $eventFilter->get();
            $taskEvents = array();
            foreach ($eventFilter as $key => $value) {
                if($value->repeat=="yes"){
                   
                    if ($value->repeat_type == 'day') {
                        $freq = 'DAILY';
                    } else if ($value->repeat_type == 'week') {
                        $freq = 'WEEKLY';
                    } else if ($value->repeat_type == 'month') {
                        $freq = 'MONTHLY';
                    } else if ($value->repeat_type == 'year') {
                        $freq = 'YEARLY';
                    }
                    $rule = (new \Recurr\Rule)
                    ->setStartDate( $value->start_date_time)
                    ->setFreq($freq)
                    ->setInterval($value->repeat_every ?? 1)
                    ->setCount($value->repeat_cycles ?? 1);

                         $rule->getString();
                    $taskEvents[] = [
                        'id' => $value->id,
                        'title' => $value->event_name,
                        'className' => $value->label_color,
                        'rrule' => $rule,
                    ];
                }else{
                    $taskEvents[] = [
                        'id' => $value->id,
                        'title' => $value->event_name,
                        'className' => $value->label_color,
                        'start' => $value->start_date_time,
                        'end' => $value->end_date_time
                    ];
                }
                
            }
            return $taskEvents;
    }
    public function store(StoreEvent $request)
    {
        $event = new Event();
        $event->event_name = $request->event_name;
        $event->where = $request->where;
        $event->description = $request->description;
        $event->category_id = $request->category_id;
        $event->event_type_id = $request->event_type_id;


        $event->start_date_time = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d') . ' ' . Carbon::createFromFormat($this->global->time_format, $request->start_time)->format('H:i:s');
        $event->end_date_time = Carbon::createFromFormat($this->global->date_format, $request->end_date)->format('Y-m-d') . ' ' . Carbon::createFromFormat($this->global->time_format, $request->end_time)->format('H:i:s');

        if ($request->repeat) {
            $event->repeat = $request->repeat;
        } else {
            $event->repeat = 'no';
        }

        $event->repeat_every = $request->repeat_count;
        $event->repeat_cycles = $request->repeat_cycles;
        $event->repeat_type = $request->repeat_type;
        $event->label_color = $request->label_color;
        $event->save();

        if ($request->all_employees) {
            $attendees = User::allEmployees();
            foreach ($attendees as $attendee) {
                EventAttendee::create(['user_id' => $attendee->id, 'event_id' => $event->id]);
            }

            Notification::send($attendees, new EventInvite($event));
        }
        if ($request->all_clients) {

            if(isset($attendees)){
                $attendees = User::allClients()->merge($attendees);
            }
            else{
                $attendees = User::allClients();
            }

            foreach ($attendees as $attendee) {
                EventAttendee::create(['user_id' => $attendee->id, 'event_id' => $event->id]);
            }

            Notification::send($attendees, new EventInvite($event));
        } 

        if ($request->user_id) {
            foreach ($request->user_id as $userId) {
                EventAttendee::firstOrCreate(['user_id' => $userId, 'event_id' => $event->id]);
            }
            $attendees = User::whereIn('id', $request->user_id)->get();
            Notification::send($attendees, new EventInvite($event));
        }

        return Reply::success(__('messages.eventCreateSuccess'));
    }

    public function edit($id)
    {
         
        $this->employees = User::allEmployees();  
        $this->clients = User::allClients();  
        $this->event = Event::with('attendee')->findOrFail($id);
        $this->categories = EventCategory::all();
        $this->eventTypes = EventType::all();
        $view = view('admin.event-calendar.edit', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function update(UpdateEvent $request, $id)
    {
        $event = Event::findOrFail($id);
        $event->event_name = $request->event_name;
        $event->where = $request->where;
        $event->description = $request->description;
        $event->event_type_id = $request->event_type_id;
        $event->category_id = $request->category_id;
        $event->start_date_time = Carbon::createFromFormat($this->global->date_format, $request->start_date)->format('Y-m-d') . ' ' . Carbon::createFromFormat($this->global->time_format, $request->start_time)->format('H:i:s');
        $event->end_date_time = Carbon::createFromFormat($this->global->date_format, $request->end_date)->format('Y-m-d') . ' ' . Carbon::createFromFormat($this->global->time_format, $request->end_time)->format('H:i:s');

        if ($request->repeat) {
            $event->repeat = $request->repeat;
        } else {
            $event->repeat = 'no';
        }

        $event->repeat_every = $request->repeat_count;
        $event->repeat_cycles = $request->repeat_cycles;
        $event->repeat_type = $request->repeat_type;
        $event->label_color = $request->label_color;
        $event->save();

        if ($request->all_employees) {
            $attendees = User::allEmployees();
            foreach ($attendees as $attendee) {
                $checkExists = EventAttendee::where('user_id', $attendee->id)->where('event_id', $event->id)->first();
                if (!$checkExists) {
                    EventAttendee::create(['user_id' => $attendee->id, 'event_id' => $event->id]);

                    //      Send notification to user
                    $notifyUser = User::withoutGlobalScope('active')->findOrFail($attendee->id);
                    $notifyUser->notify(new EventInvite($event));
                }
            }
        }
        if ($request->all_clients) {
            $attendees = User::allClients();
            foreach ($attendees as $attendee) {
                $checkExists = EventAttendee::where('user_id', $attendee->id)->where('event_id', $event->id)->first();
                if (!$checkExists) {
                    EventAttendee::create(['user_id' => $attendee->id, 'event_id' => $event->id]);

                    //      Send notification to user
                    $notifyUser = User::withoutGlobalScope('active')->findOrFail($attendee->id);
                    $notifyUser->notify(new EventInvite($event));
                }
            }
        }

        if ($request->user_id) {
            foreach ($request->user_id as $userId) {
                $checkExists = EventAttendee::where('user_id', $userId)->where('event_id', $event->id)->first();
                if (!$checkExists) {
                    EventAttendee::create(['user_id' => $userId, 'event_id' => $event->id]);

                    //      Send notification to user
                    $notifyUser = User::withoutGlobalScope('active')->findOrFail($userId);
                    $notifyUser->notify(new EventInvite($event));
                }
            }
        }

        return Reply::success(__('messages.eventCreateSuccess'));
    }

    public function show($id)
    {
        $this->startDate = explode(' ', request('start'));
        $this->startDate = Carbon::parse($this->startDate[0]);
        $this->event = Event::with(['attendee', 'attendee.user'])->findOrFail($id);
        return view('admin.event-calendar.show', $this->data);
    }

    public function removeAttendee(Request $request)
    {
        EventAttendee::destroy($request->attendeeId);
        $id = $request->event_id;
        $employees = User::doesntHave('attendee', 'and', function ($query) use ($id) {
            $query->where('event_id', $id);
        })
            ->select('users.id', 'users.name', 'users.email', 'users.created_at')
            ->get();

        $employeesArray = [];

        foreach ($employees as $key => $employee) {
            $employeesArray[$key]['id'] = $employee->id;
            $employeesArray[$key]['text'] = (auth()->user()->id == $employee->id) ? $employeesArray[$key]['text'] = $employee->name . ' (You)' : $employeesArray[$key]['text'] = $employee->name;
        }

        return Reply::dataOnly(['status' => 'success', 'employees' => $employeesArray]);
    }

    public function destroy($id)
    {
        Event::destroy($id);
        return Reply::success(__('messages.eventDeleteSuccess'));
    }
}

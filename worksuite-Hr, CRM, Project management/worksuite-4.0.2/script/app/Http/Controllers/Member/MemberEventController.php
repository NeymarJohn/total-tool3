<?php

namespace App\Http\Controllers\Member;

use App\Event;
use App\EventAttendee;
use App\Events\EventInviteEvent;
use App\Helper\Reply;
use App\Http\Requests\Events\StoreEvent;
use App\Http\Requests\Events\UpdateEvent;
use App\Notifications\EventInvite;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class MemberEventController extends MemberBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.Events';
        $this->pageIcon = 'icon-calender';
        $this->middleware(function ($request, $next) {
            if (!in_array('events', $this->user->modules)) {
                abort(403);
            }
            return $next($request);
        });
    }

    public function index()
    {
        if ($this->user->can('view_events')) {
            $this->events = Event::all();
        } else {
            $this->events = Event::join('event_attendees', 'event_attendees.event_id', '=', 'events.id')
                ->where('event_attendees.user_id', $this->user->id)
                ->select('events.*')
                ->get();
        }
        $this->employees = User::all();

        return view('member.event-calendar.index', $this->data);
    }

    public function show($id)
    {
        $this->event = Event::findOrFail($id);
        return view('member.event-calendar.show', $this->data);
    }

    public function store(StoreEvent $request)
    {
        $event = new Event();
        $event->event_name = $request->event_name;
        $event->where = $request->where;
        $event->description = $request->description;
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
            event(new EventInviteEvent($event, $attendees));
        }

        if ($request->user_id) {
            foreach ($request->user_id as $userId) {
                EventAttendee::firstOrCreate(['user_id' => $userId, 'event_id' => $event->id]);
            }
            $attendees = User::whereIn('id', $request->user_id)->get();
            event(new EventInviteEvent($event, $attendees));
        }

        return Reply::success(__('messages.eventCreateSuccess'));
    }

    public function update(UpdateEvent $request, $id)
    {
        $event = Event::findOrFail($id);
        $event->event_name = $request->event_name;
        $event->where = $request->where;
        $event->description = $request->description;
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
                    event(new EventInviteEvent($event, $notifyUser));
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
                    event(new EventInviteEvent($event, $notifyUser));
                }
            }
        }

        return Reply::success(__('messages.eventCreateSuccess'));
    }

    public function removeAttendee(Request $request)
    {
        EventAttendee::destroy($request->attendeeId);
        return Reply::dataOnly(['status' => 'success']);
    }

    public function destroy($id)
    {
        Event::destroy($id);
        return Reply::success(__('messages.eventDeleteSuccess'));
    }

    public function edit($id)
    {
        if ($this->user->can('edit_events')) {
            $this->employees = User::doesntHave('attendee', 'and', function ($query) use ($id) {
                $query->where('event_id', $id);
            })
                ->select('users.id', 'users.name', 'users.email', 'users.created_at')
                ->get();
            $this->event = Event::findOrFail($id);
            $view = view('member.event-calendar.edit', $this->data)->render();
            return Reply::dataOnly(['status' => 'success', 'view' => $view]);
        }
        return abort(403);
    }
}

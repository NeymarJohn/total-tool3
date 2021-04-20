<?php

namespace App\Http\Controllers\Client;

use App\Event;
use App\ModuleSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientEventController extends ClientBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.Events';
        $this->pageIcon = 'icon-calender';
        $this->middleware(function ($request, $next) {
            if(!in_array('events',$this->user->modules)){
                abort(403);
            }
            return $next($request);
        });

    }

    public function index(){
        $this->events = Event::join('event_attendees', 'event_attendees.event_id', '=', 'events.id')
            ->where('event_attendees.user_id', $this->user->id)
            ->select('events.*')
            ->get();

        return view('client.event-calendar.index', $this->data);
    }

    public function show($id){
        $this->event = Event::findOrFail($id);
        return view('client.event-calendar.show', $this->data);
    }
}

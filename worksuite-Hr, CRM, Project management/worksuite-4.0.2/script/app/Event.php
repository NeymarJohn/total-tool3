<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends BaseModel
{
    protected $dates = ['start_date_time', 'end_date_time'];
    protected $fillable = ['start_date_time', 'end_date_time', 'event_name', 'where', 'description'];

    public function attendee()
    {
        return $this->hasMany(EventAttendee::class, 'event_id');
    }


    public function getUsers()
    {
        $userArray = [];
        foreach ($this->attendee as $attendee) {
            array_push($userArray, $attendee->user()->select('id', 'email', 'name')->first());
        }
        return collect($userArray);
    }
}

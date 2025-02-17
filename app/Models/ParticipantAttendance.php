<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantAttendance extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'event_step_id',
        'attendance_arrival',
        'attendance_departure',
        'attendance_arrival_time',
        'attendance_departure_time'
    ];

    protected $casts = [
        'attendance_arrival' => 'boolean',
        'attendance_departure' => 'boolean',
        'attendance_arrival_time' => 'datetime',
        'attendance_departure_time' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function eventStep()
    {
        return $this->belongsTo(EventStep::class);
    }
}

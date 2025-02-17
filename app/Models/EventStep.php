<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventStep extends Model
{
    protected $fillable = [
        'event_id',
        'step_name',
        'step_order',
        'description',
        'amount',
        'status',
        'is_free'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_free' => 'boolean',
        'status' => 'string'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function participantSteps()
    {
        return $this->hasMany(ParticipantStep::class);
    }

    public function attendances()
    {
        return $this->hasMany(ParticipantAttendance::class);
    }
}

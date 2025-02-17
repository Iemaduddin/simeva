<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventFile extends Model
{
    protected $fillable = [
        'event_id',
        'participant_id',
        'step_id',
        'file_path'
    ];

    protected $casts = [
        'uploaded_at' => 'datetime'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function participant()
    {
        return $this->belongsTo(EventParticipant::class);
    }

    public function step()
    {
        return $this->belongsTo(EventStep::class);
    }
}
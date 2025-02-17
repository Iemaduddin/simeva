<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'status',
    ];

    protected $casts = [
        'status' => 'string'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function files()
    {
        return $this->hasMany(EventFile::class, 'participant_id');
    }

    public function formResponses()
    {
        return $this->hasMany(FormResponse::class, 'participant_id');
    }
}

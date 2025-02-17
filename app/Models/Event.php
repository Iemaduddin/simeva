<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Event extends Model
{
    use HasUuids;
    protected $fillable = [
        'title',
        'theme',
        'description',
        'scope',
        'date_event_start',
        'date_event_end',
        'registration_date_start',
        'registration_date_end',
        'registration_extended',
        'location',
        'remaining_quota',
        'quota',
        'event_mode',
        'benefit',
        'sponsored_by',
        'media_partner',
        'additional_links',
        'event_leader',
        'contact_person',
        'is_draft',
        'is_publish',
        'status'
    ];

    protected $casts = [
        'date_event_start' => 'datetime',
        'date_event_end' => 'datetime',
        'registration_date_start' => 'datetime',
        'registration_date_end' => 'datetime',
        'registration_extended' => 'boolean',
        'is_draft' => 'boolean',
        'is_publish' => 'boolean',
        'scope' => 'string',
        'event_mode' => 'string',
        'status' => 'string'
    ];

    public function categories()
    {
        return $this->belongsToMany(CategoryOfEvent::class, 'event_categories', 'event_id', 'category_id');
    }

    public function speakers()
    {
        return $this->belongsToMany(Speaker::class, 'event_speakers')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function timelines()
    {
        return $this->hasMany(Timeline::class);
    }

    public function steps()
    {
        return $this->hasMany(EventStep::class);
    }

    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function prices()
    {
        return $this->hasMany(EventPrice::class);
    }

    public function forms()
    {
        return $this->hasMany(EventForm::class);
    }

    public function calendar()
    {
        return $this->hasOne(Calendar::class);
    }
    public function certificates()
    {
        return $this->hasMany(ParticipantEventCertificate::class, 'event_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventForm extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'description',
        'is_required'
    ];

    protected $casts = [
        'is_required' => 'boolean'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function fields()
    {
        return $this->hasMany(FormField::class, 'form_id');
    }

    public function responses()
    {
        return $this->hasMany(FormResponse::class, 'form_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'organization',
        'title',
        'biography',
        'personal_document'
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_speakers')
            ->withPivot('role')
            ->withTimestamps();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantEventCertificate extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'certificate_file_path'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
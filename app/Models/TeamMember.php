<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = ['name', 'organizer_id', 'level', 'position'];

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    public function attendances()
    {
        return $this->hasMany(EventAttendance::class, 'team_member_id', 'id');
    }
}

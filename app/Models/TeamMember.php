<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $fillable = ['organizer_id', 'position'];

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }
}

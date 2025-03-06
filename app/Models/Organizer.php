<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Organizer extends Model
{
    use HasUuids;
    protected $fillable = [
        'shorten_name',
        'vision',
        'mision',
        'description',
        'link_ig',
        'link_x',
        'link_youtube',
        'link_tiktok',
        'link_linkedin',
        'whatsapp_number',
        'user_id',
        'organizer_type',
        'logo'
    ];

    protected $casts = [
        'organizer_type' => 'string'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class);
    }
}

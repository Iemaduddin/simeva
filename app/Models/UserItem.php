<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserItem extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'asset_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}
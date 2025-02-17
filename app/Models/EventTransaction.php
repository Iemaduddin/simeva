<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'event_id',
        'event_amount',
        'total_amount',
        'status',
        'payment_date',
        'proof_of_payment',
        'signature'
    ];

    protected $casts = [
        'event_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'payment_date' => 'datetime',
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
}
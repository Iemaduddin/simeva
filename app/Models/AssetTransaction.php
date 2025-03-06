<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AssetTransaction extends Model
{
    use HasUuids;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'booking_id',
        'user_id',
        'invoice_number',
        'amount',
        'va_number',
        'va_expiry',
        'tax',
        'proof_of_payment',
        'status',
        'payment_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'status' => 'string'
    ];

    public function booking()
    {
        return $this->belongsTo(AssetBooking::class, 'booking_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

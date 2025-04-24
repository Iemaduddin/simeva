<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AssetBookingDocument extends Model
{
    protected $fillable = [
        'booking_id',
        'event_id',
        'document_path',
        'document_type',
        'uploaded_by'
    ];

    protected $casts = [
        'document_type' => 'string'
    ];

    public function booking()
    {
        return $this->belongsTo(AssetBooking::class, 'booking_id');
    }
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

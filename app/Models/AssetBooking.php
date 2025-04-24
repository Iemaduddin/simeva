<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class AssetBooking extends Model
{
    use HasUuids;
    protected $fillable = [
        'asset_id',
        'user_id',
        'event_id',
        'external_user',
        'booking_number',
        'asset_category_id',
        'usage_date_start',
        'usage_date_end',
        'loading_date_start',
        'loading_date_end',
        'unloading_date',
        'usage_event_name',
        'payment_type',
        'booking_duration',
        'total_amount',
        'status',
        'reason'
    ];

    protected $casts = [
        'usage_date_start' => 'datetime',
        'usage_date_end' => 'datetime',
        'loading_date_start' => 'datetime',
        'loading_date_end' => 'datetime',
        'unloading_date' => 'date',
        'payment_type' => 'string',
        'status' => 'string'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
    public function asset_category()
    {
        return $this->belongsTo(AssetCategory::class, 'asset_category_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function transactions()
    {
        return $this->hasMany(AssetTransaction::class, 'booking_id');
    }

    public function documents()
    {
        return $this->hasMany(AssetBookingDocument::class, 'booking_id');
    }
    public function calendar()
    {
        return $this->hasOne(Calendar::class);
    }
}

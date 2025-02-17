<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Asset extends Model
{
    use HasUuids;
    protected $fillable = [
        'name',
        'description',
        'type',
        'facility_scope',
        'jurusan_id',
        'facility',
        'available_at',
        'asset_images',
        'booking_type',
        'status'
    ];

    protected $casts = [
        'type' => 'string',
        'facility_scope' => 'string',
        'booking_type' => 'string',
        'status' => 'string'
    ];
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }
    public function categories()
    {
        return $this->hasMany(AssetCategory::class);
    }
    public function bookings()
    {
        return $this->hasMany(AssetBooking::class);
    }

    public function calendar()
    {
        return $this->hasOne(Calendar::class);
    }
}

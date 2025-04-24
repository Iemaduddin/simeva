<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class AssetCategory extends Model
{
    protected $fillable =
    [
        'asset_id',
        'category_name',
        'external_price',
        'internal_price_percentage',
        'social_price_percentage',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
}

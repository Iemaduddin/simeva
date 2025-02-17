<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryOfEvent extends Model
{
    protected $table = 'categories_of_event';

    protected $fillable = ['name'];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_categories', 'category_id', 'event_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    protected $fillable = [
        'form_id',
        'field_label',
        'field_type',
        'is_required'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'field_type' => 'string'
    ];
    public function form()
    {
        return $this->belongsTo(EventForm::class, 'form_id');
    }

    public function responses()
    {
        return $this->hasMany(FormResponse::class, 'field_id');
    }
}
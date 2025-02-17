<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormResponse extends Model
{
    protected $fillable = [
        'form_id',
        'participant_id',
        'field_id',
        'response_value'
    ];

    public function form()
    {
        return $this->belongsTo(EventForm::class, 'form_id');
    }

    public function participant()
    {
        return $this->belongsTo(EventParticipant::class, 'participant_id');
    }

    public function field()
    {
        return $this->belongsTo(FormField::class, 'field_id');
    }
}
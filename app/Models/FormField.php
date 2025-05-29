<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    protected $fillable = ['country_id', 'label', 'type', 'is_required', 'category', 'options'];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function values()
    {
        return $this->hasMany(SubmissionValue::class);
    }
}

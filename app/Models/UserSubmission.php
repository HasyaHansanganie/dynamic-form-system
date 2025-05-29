<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubmission extends Model
{
    protected $fillable = ['country_id'];

    public function values()
    {
        return $this->hasMany(SubmissionValue::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}

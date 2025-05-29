<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionValue extends Model
{
    protected $fillable = ['user_submission_id', 'form_field_id', 'value'];

    public function field()
    {
        return $this->belongsTo(FormField::class);
    }

    public function submission()
    {
        return $this->belongsTo(UserSubmission::class);
    }
}

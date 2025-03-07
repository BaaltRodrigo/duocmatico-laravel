<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Section extends Model
{

    public $fillable = ['code', 'shift', 'year', 'season', 'course_id'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Section extends Model
{

    public $fillable = ['code', 'shift', 'year', 'season', 'course_id'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function schedules(): BelongsToMany
    {
        return $this->belongsToMany(Schedule::class, 'section_schedule');
    }

    public function campuses(): BelongsToMany
    {
        return $this->belongsToMany(Campus::class, 'section_campus', 'section_id', 'campus_id');
    }

    public function careers(): BelongsToMany
    {
        return $this->belongsToMany(Career::class, 'section_career', 'section_id', 'career_id');
    }
}

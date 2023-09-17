<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Section extends Model
{
    use HasFactory;

    protected $table = 'academic_charge_subject';

    // The attributes that are mass assignable.
    protected $fillable = [
        'academic_charge_id',
        'subject_id',
        'code',
        'shift',
        'teacher',
    ];

    public function academicCharge(): BelongsTo
    {
        return $this->belongsTo(AcademicCharge::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function schedules(): BelongsToMany
    {
        return $this->belongsToMany(Schedule::class);
    }
}

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

    protected $with = ['subject', 'schedules', 'career', 'school'];

    // The attributes that are mass assignable.
    protected $fillable = [
        'academic_charge_id',
        'subject_id',
        'career_id',
        'school_id',
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

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function schedules(): BelongsToMany
    {
        return $this->belongsToMany(Schedule::class);
    }

    public function scopeFromAcademicCharge($query, $academicChargeId)
    {
        return $query->where('academic_charge_id', $academicChargeId);
    }

    public function scopeCareerOrSchool($query, $resource_type, $resource_id)
    {
        return $query->where($resource_type.'_id', $resource_id);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Calendar extends Model
{
    use HasFactory;

    protected $guard_name = 'web';

    public $fillable = [
        'uuid',
        'is_public',
        'name',
        'description',
        'options', // Custom data that user provides
        'user_id',
        'academic_charge_id',
        'calendarable_id',
        'calendarable_type',
    ];

    public function academicCharge(): BelongsTo
    {
        return $this->belongsTo(AcademicCharge::class, 'academic_charge_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function charge(): BelongsTo
    {
        return $this->belongsTo(AcademicCharge::class, 'academic_charge_id');
    }

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'calendar_section', 'calendar_id', 'section_id');
    }

    public function calendarable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getCalendarableClass()
    {
        return $this->calendarable_type;
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }
}

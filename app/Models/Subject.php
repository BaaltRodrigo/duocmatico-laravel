<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    public $fillable = [
        'code', // Abbreviation like ASY5512
        'name',
        'level',
        'career_id',
        'school_id', // This could be moved to a separate table... or deleted
    ];

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }    

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }
}

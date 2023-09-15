<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subject extends Model
{
    use HasFactory;

    public $fillable = [
        'name',
        'career_id',
        'code', // Abbreviation like ASY5512
        'level',
        'school', // This could be moved to a separate table... or deleted
    ];

    // TODO: Belongs to a career
    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }

    // TODO: Has many sections
}

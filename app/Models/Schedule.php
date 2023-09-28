<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Schedule extends Model
{
    use HasFactory;

    public $fillable = [
        'day',
        'classroom',
        'times', // Times is a string with the day and times
    ];

    public function sections(): BelongsToMany
    {
        return $this->belongsToMany(Section::class);
    }
}

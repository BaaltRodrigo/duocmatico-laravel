<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    use HasFactory;

    // protected $with = ['career', 'school'];

    public $fillable = [
        'code', // Abbreviation like ASY5512
        'name',
        'level',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(Section::class);
    }
}

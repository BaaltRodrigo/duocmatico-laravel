<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Schedule extends Model
{
    public $fillable = ["day", "start", "end"];

    const UPDATED_AT = null;
    public $timestamps = ['created_at'];

    public function section(): BelongsToMany
    {
        return $this->belongsToMany(Section::class, 'section_schedule');
    }
}

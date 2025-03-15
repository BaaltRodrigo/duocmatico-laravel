<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    public $fillable = ['name'];

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'section_career', 'career_id', 'section_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    public $fillable = ['name'];

    protected $table = "campuses";

    public function sections()
    {
        return $this->belongsToMany(Section::class, 'section_campus', 'campus_id', 'section_id');
    }
}

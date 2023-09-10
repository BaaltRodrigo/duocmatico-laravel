<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicCharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'year',
        'semester',
        'is_hidden',
    ];
}

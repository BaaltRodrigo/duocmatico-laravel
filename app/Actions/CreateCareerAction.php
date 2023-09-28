<?php

namespace App\Actions;

use App\Models\Career;
use Illuminate\Support\Str;

class CreateCareerAction
{
    public function execute(String $name)
    {
        // The career is created if it doesn't exist
        $career = Career::firstOrCreate([
            'name' => Str::slug($name),
        ]);

        return $career;
    }
}
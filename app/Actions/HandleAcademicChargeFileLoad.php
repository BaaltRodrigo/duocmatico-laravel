<?php

namespace App\Actions;

use App\Models\AcademicCharge;
use App\Models\Career;
use App\Models\School;  
use App\Models\Section;
use App\Models\Schedule;
use App\Models\Subject;
use Illuminate\Support\Str;


class HandleAcademicChargeFileLoad
{
    public function execute(AcademicCharge $charge, array $fileLines)
    {
        // Only for log purposes
        $counters = [
            'careers' => 0,
            'schools' => 0,
            'subjects' => 0,
            'sections' => 0,
            'schedules' => 0,
            'duplicates' => 0
        ];

        // map the headers to line numbers
        $headers = array_shift($fileLines);
        $headers = array_flip($headers);

        // The following lines will create every necessary model for the academic charge
        foreach ($fileLines as $line) {
            // Create or get the Career
            $career = Career::firstOrCreate([
                'name' => Str::slug($line[$headers['Carrera']])
            ]);

            if ($career->wasRecentlyCreated) $counters['careers']++;

            // Create or get the school
            $school = School::firstOrCreate([
                'name' => Str::slug($line[$headers['Escuela']])
            ]);

            if ($school->wasRecentlyCreated) $counters['schools']++;
            
            // convert level to integer. Non integer strings casted to 0
            $level = (int) $line[$headers['Nivel']];
            // Create or get the Subject from the career
            $subject = Subject::firstOrCreate([
                'career_id' => $career->id,
                'name' => Str::slug($line[$headers['Asignatura']]),
                'code' => Str::slug($line[$headers['Sigla']]),
                'level' => $level,
                'school_id' => $school->id,
            ]);

            if ($subject->wasRecentlyCreated) $counters['subjects']++;

            // Create or get the Section
            $section = Section::firstOrCreate([
                'academic_charge_id' => $charge->id,
                'subject_id' => $subject->id,
                'code' => $line[$headers['Sección']],
                'shift' => Str::slug($line[$headers['Jornada']]),
                'teacher' => Str::slug($line[$headers['Docente']]),
            ]);

            if ($section->wasRecentlyCreated) $counters['sections']++;

            // Create or get the schedule of the row
            $schedule = Schedule::firstOrCreate([
                'day' => Str::slug($line[$headers['Día']]),
                'classroom' => Str::slug($line[$headers['Sala']]),
                'times' => $line[$headers['Horario']], // Cannot be a slug or we lost the dots for the hours
            ]);

            if ($schedule->wasRecentlyCreated) $counters['schedules']++;

            // Attach the schedule to the section. This may cause a duplicate
            try {
                $section->schedules()->attach($schedule->id);
            } catch (\Illuminate\Database\UniqueConstraintViolationException $th) {
                $counters['duplicates']++;
            }
        }

        info('Lines inside file: ' . count($fileLines));
        info('Careers created: ' . $counters['careers']);
        info('Schools created: ' . $counters['schools']);
        info('Subjects created: ' . $counters['subjects']);
        info('Sections created: ' . $counters['sections']);
        info('Schedules created: ' . $counters['schedules']);
        info('Duplicates schedules: ' . $counters['duplicates']);

        return $charge;
    }
}
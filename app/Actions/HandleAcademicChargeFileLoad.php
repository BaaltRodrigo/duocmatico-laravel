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
    public static function execute($validated): AcademicCharge
    {
        // Only for log purposes
        $counters = [
            'careers' => 0,
            'schools' => 0,
            'subjects' => 0,
            'sections' => 0,
            'schedules' => 0,
            'duplicate schedules' => 0,
        ];

        // Read the file uploaded
        $csvData = array_map('str_getcsv', file($validated['file']));
        $headers = array_shift($csvData);
        // Replace the first element special character \u{FEFF} (zero width no-break space)
        $headers[0] = ltrim($headers[0], "\u{FEFF}");
        //Map the headers to line numbers
        $headers = array_flip($headers);

        // Create the academic charge
        $charge = AcademicCharge::create([
            'year' => $csvData[1][$headers['Año']],
            'semester' => $csvData[1][$headers['Período']],
            'name' => $csvData[1][$headers['Sede']],
        ]);

        // The following lines will create every necessary model for the academic charge
        foreach ($csvData as $line) {
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
                'name' => Str::slug($line[$headers['Asignatura']]),
                'code' => Str::slug($line[$headers['Sigla']]),
                'level' => $level,
            ]);

            if ($subject->wasRecentlyCreated) $counters['subjects']++;

            // Create or get the Section
            $section = Section::firstOrCreate([
                'academic_charge_id' => $charge->id,
                'subject_id' => $subject->id,
                'code' => $line[$headers['Sección']],
                'shift' => Str::slug($line[$headers['Jornada']]),
                'teacher' => Str::slug($line[$headers['Docente']]),
                'career_id' => $career->id,
                'school_id' => $school->id,
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
                $counters['duplicate schedules']++;
            }
        }

        info('Lines inside file: ' . count($csvData));
        info('Careers created: ' . $counters['careers']);
        info('Schools created: ' . $counters['schools']);
        info('Subjects created: ' . $counters['subjects']);
        info('Sections created: ' . $counters['sections']);
        info('Schedules created: ' . $counters['schedules']);
        info('Duplicates schedules: ' . $counters['duplicate schedules']);

        return $charge;
    }
}
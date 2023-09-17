<?php

namespace Database\Seeders;

use App\Models\AcademicCharge;
use App\Models\Career;
use App\Models\School;
use App\Models\Section;
use App\Models\Schedule;
use App\Models\Subject;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AcademicChargeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This seeder is a full database seeder. This will create academic charges
     * and the models related to an academic charge.
     */
    public function run(): void
    {
        $filePath = base_path('database/data/SAN-JOAQUIN.csv');

        $csvData = array_map('str_getcsv', file($filePath));
        $headers = array_shift($csvData); // Remove the headers from the csv data

        // Create the academic charge
        $academicCharge = AcademicCharge::firstOrCreate([
            'name' => Str::slug($csvData[0][2]), // Column called Sede
            'year' => $csvData[0][0],
            'semester' => $csvData[0][1], // Column called Periodo
            'is_hidden' => false,
        ]);

        // The following lines will create every necessary model for the academic charge
        foreach ($csvData as $line) {
            // Create or get the Career
            $career = Career::firstOrCreate([
                'name' => Str::slug($line[4])
            ]);

            // Create or get the school
            $school = School::firstOrCreate([
                'name' => Str::slug($line[3])
            ]);
            
            // Create or get the Subject from the career
            $subject = Subject::firstOrCreate([
                'career_id' => $career->id,
                'name' => Str::slug($line[10]), // Column called Asignatura
                'code' => Str::slug($line[9]), // Column called Sigla
                'level' => $line[8], // Column called Nivel
                'school_id' => $school->id,
            ]);

            // Create or get the Section
            $section = Section::firstOrCreate([
                'academic_charge_id' => $academicCharge->id,
                'subject_id' => $subject->id,
                'code' => $line[11], // Column called Seccion
                'shift' => Str::slug($line[7]), // Column called Jornada
                'teacher' => Str::slug($line[14]), // Column called Professor
            ]);

            // Create or get the schedule of the row
            $schedule = Schedule::firstOrCreate([
                'day' => Str::slug($line[15]), // Column called Dia
                'classroom' => Str::slug($line[13]), // Column called Sala
                'times' => $line[12], // Column called Horario. Cannot be a slug or we lost the dots for the hours
            ]);

            // Attach the schedule to the section. This may cause a duplicate
            try {
                $section->schedules()->attach($schedule->id);
            } catch (\Illuminate\Database\UniqueConstraintViolationException $th) {
                info("Duplicate entry for schedule {$schedule->id} and section {$section->id}");
            }
        }
    }
}

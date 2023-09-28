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
use Illuminate\Support\Facades\DB;

class CsvDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filesModels = [
            ['path' => base_path('database/csv/academic_charges.csv'), 'model' => AcademicCharge::class],
            ['path' => base_path('database/csv/careers.csv'), 'model' => Career::class],
            ['path' => base_path('database/csv/schools.csv'), 'model' => School::class],
            ['path' => base_path('database/csv/subjects.csv'), 'model' => Subject::class],
            ['path' => base_path('database/csv/sections.csv'), 'model' => Section::class],
            ['path' => base_path('database/csv/schedules.csv'), 'model' => Schedule::class],
        ];

        foreach ($filesModels as $seed) {
            $csvData = array_map('str_getcsv', file($seed['path']));

            $headers = array_shift($csvData);
            $headers[0] = 'id';  

            // make a collection of combined array between csv lines and headers
            $data = collect($csvData)->map(function ($line) use ($headers) {
                return array_combine($headers, $line);
            });

            // generate chunks of 500 records to insert
            $data->chunk(500)->each(function ($chunk) use ($seed) {
                $seed['model']::insert($chunk->toArray());
            });
        }

        // Many to many relationships needs to be seeded manually
        $shceduleSectionPath = base_path('database/csv/schedule_section.csv');
        $scheduleSectionCsvData = array_map('str_getcsv', file($shceduleSectionPath));
        $scheduleSectionHeaders = array_shift($scheduleSectionCsvData);
        $scheduleSectionHeaders[0] = 'schedule_id';

        $this->command->info('seeding many to many');

        // raw insert the data into the pivot table
        DB::table('schedule_section')->insert(
            collect($scheduleSectionCsvData)->map(function ($line) use ($scheduleSectionHeaders) {
                return array_combine($scheduleSectionHeaders, $line);
            })->toArray()
        );

    }
}

<?php

namespace App\Actions;

use App\Models\Campus;
use App\Models\Career;
use App\Models\Course;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;

class ImportAcademicData
{
    public function handle($request)
    {
        $file = $request['file'];
        $year = $request['year'];
        $season = $request['season'];

        // Load the excel file
        $spreadsheet = IOFactory::load($file->getRealPath());
        $sheet = $spreadsheet->getSheet(0);
        $rows = $sheet->toArray();

        // Extract headers and normalize to remove accents and spaces
        $headers = array_map('strtolower', $rows[0]);
        $headers = array_map(function ($header) {
            return Str::slug($header);
        }, $headers);
        // TODO: Run the validation here

        // Get column indexes dynamically
        $indexes = array_flip($headers);
        // echo '<pre>';
        // print_r($indexes);
        // echo '</pre>';
        // exit;

        foreach (array_slice($rows, 1) as $row) {
            $this->processRow($row, $indexes, $year, $season);
        }
    }

    private function processRow($row, $indexes, $year, $season)
    {
        // Extract the data using the dynamic headers
        $campus         = $row[$indexes['sede']];
        $career         = $row[$indexes['carrera']];
        $plan           = $row[$indexes['plan']];
        $shift          = $row[$indexes['jornada']];
        $level          = $row[$indexes['nivel']];
        $course_code    = $row[$indexes['sigla']];
        $course_name    = $row[$indexes['asignatura']];
        $section_code   = $row[$indexes['seccion']];
        $schedule       = $row[$indexes['horario']];
        $teacher        = $row[$indexes['docente']];

        // Check if the career and campus exists
        $career = Career::firstOrCreate(['name' => $career]);
        $campus = Campus::firstOrCreate(['name' => $campus]);

        // Create the course
        $course = Course::firstOrCreate([
            'code' => $course_code,
            'name' => $course_name,
            'level' => $level,
        ]);

        // Create the section
        $slugShift = Str::slug($shift);
        $section = $course->sections()->firstOrCreate([
            'code' => $section_code,
            'shift' => $slugShift,
            'year' => $year,
            'season' => $season,
        ]);
    }
}

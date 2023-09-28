<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcademicChargeFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $filePaths = [
            base_path('database/data/PUENTE-ALTO 2023-1.csv'),
            base_path('database/data/SAN-JOAQUIN 2023-1.csv'),
            base_path('database/data/SAN-JOAQUIN 2023-2.csv'),
        ];

        foreach ($filePaths as $path) {
            $this->command->info('Loading file ' . $path);

            // read the csv file
            $csvData = array_map('str_getcsv', file($path));

            // Create the academic charge
            // Using the second line as the first line is the header
            $charge = \App\Models\AcademicCharge::create([
                'name' => $csvData[1][0],
                'year' => $csvData[1][1],
                'semester' => $csvData[1][2],
            ]);

            // use the action to handle the file load
            app(\App\Actions\HandleAcademicChargeFileLoad::class)->execute($charge, $csvData);

            $this->command->info('Academic charge ' . $charge->name . ' loaded');
        }
    }
}

<?php

namespace Database\Seeders;
use App\Models\Counties;
use App\Models\Towns;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TownsFromCsvSeeder extends Seeder
{
    public function run()
    {
        $csvFilePath = storage_path('app/iranyitoszamok.csv');

        if (($handle = fopen($csvFilePath, 'r')) !== false) {
            
            fgetcsv($handle, 1000, ';'); 

            while (($row = fgetcsv($handle,1000, ';')) !== false) {
                $row = array_map(function($value) {
                    return trim(preg_replace("/\r|\n/", "", $value)); 
                }, $row);
                if (count($row) < 3) {
                    continue; 
                }
                if (empty($row[0]) || empty($row[1]) || trim($row[0]) == "Postal Code
                Irányítószám" || trim($row[1]) == "Place Name
                Település" || trim($row[2]) == "County
                Megye") {
                    continue; 
                }

                $zipCode = trim($row[0]);
                $townName = trim($row[1]);
                $countyName = trim($row[2]);

                if (empty($row[0]) || empty($row[1]) || 
                    $this->isHeaderRow($row)) {
                    continue; 
                }

                $county = Counties::firstOrCreate(
                    ['name' => $countyName]
                );

                Towns::create([
                    'name' => $townName,
                    'zip_code' => $zipCode,
                    'county_id' => $county->id,
                ]);
            }

            fclose($handle);
        } else {
            Log::error("Could not open the CSV file at path: {$csvFilePath}");
        }
    }
    private function isHeaderRow($row)
{
    $headerValues = [
        'Postal Code' => 0,
        'Place Name' => 1,
        'County' => 2,
    ];

    foreach ($headerValues as $header => $index) {
        if (stripos($row[$index], $header) !== false) {
            return true;
        }
    }

    return false;
}
} 


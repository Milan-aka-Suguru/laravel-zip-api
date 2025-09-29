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
        // Define the path to the CSV file
        $csvFilePath = storage_path('app/iranyitoszamok.csv');

        // Open the CSV file for reading
        if (($handle = fopen($csvFilePath, 'r')) !== false) {
            
            // Skip the header row by reading and discarding it
            fgetcsv($handle, 1000, ';'); // This skips the header row

            // Loop through each row in the CSV file
            while (($row = fgetcsv($handle,1000, ';')) !== false) {
                $row = array_map(function($value) {
                    return trim(preg_replace("/\r|\n/", "", $value)); // Remove any carriage returns or newlines
                }, $row);
                if (count($row) < 3) {
                    continue; // Skip rows that don't have the required number of columns
                }
                // Clean up and ignore empty lines (or malformed rows)
                if (empty($row[0]) || empty($row[1]) || trim($row[0]) == "Postal Code
                Irányítószám" || trim($row[1]) == "Place Name
                Település" || trim($row[2]) == "County
                Megye") {
                    continue; // Skip empty rows
                }

                // Extract values from the row
                $zipCode = trim($row[0]);
                $townName = trim($row[1]);
                $countyName = trim($row[2]);

                // Skip the row if it contains any unexpected blank fields
                if (empty($row[0]) || empty($row[1]) || 
                    $this->isHeaderRow($row)) {
                    continue; // Skip empty rows or malformed data
                }

                // Find or create the County, ensuring no duplicates
                $county = Counties::firstOrCreate(
                    ['name' => $countyName]
                );

                // Create the Town and associate it with the county
                Towns::create([
                    'name' => $townName,
                    'zip_code' => $zipCode,
                    'county_id' => $county->id,
                ]);
            }

            // Close the file after processing
            fclose($handle);
        } else {
            // Log an error if the file cannot be opened
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

    // If any of the row elements match the header columns, return true
    foreach ($headerValues as $header => $index) {
        if (stripos($row[$index], $header) !== false) {
            return true;
        }
    }

    return false;
}
} 


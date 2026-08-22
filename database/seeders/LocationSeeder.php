<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\State;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = fopen(
            database_path('/seeders/data/bd_locations.csv'),
            'r'
        );

        while(($row = fgetcsv($file)) !== false) {
            $stateName = trim($row[0]);
            $cityName = trim($row[1]);

            $state = State::firstOrCreate([
                'name' => $stateName
            ]);

            City::firstOrCreate([
                'state_id' => $state->id,
                'name' => $cityName
            ]);
        }

        fclose($file);
    }
}

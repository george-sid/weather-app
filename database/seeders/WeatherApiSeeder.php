<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\WeatherApi;

class WeatherApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WeatherApi::create([
            'name' => 'Open meteo',
            'website_url' => 'https://open-meteo.com/',
        ]);
        WeatherApi::create([
            'name' => 'Weather api',
            'website_url' => 'https://www.weatherapi.com/',
        ]);

    }
}

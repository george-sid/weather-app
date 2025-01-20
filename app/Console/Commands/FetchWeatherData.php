<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OpenMeteoService;
use App\Services\WeatherApiService;
use App\Models\WeatherApi;
use App\Models\Location;
use App\Models\WeatherApiData;

class FetchWeatherData extends Command
{
    protected $signature = 'weather:fetch';
    protected $description = 'Fetch weather data from different APIs and save to the database';

    protected $openMeteoService;
    protected $weatherApiService;

    public function __construct(OpenMeteoService $openMeteoService, WeatherApiService $weatherApiService)
    {
        parent::__construct();
        $this->openMeteoService = $openMeteoService;
        $this->weatherApiService = $weatherApiService;
    }

    public function handle()
    {
        $types = explode(',', env('FOREST_TYPE', 'hourly,daily')); 
        $apis = WeatherApi::all();
        $locations = Location::all();

        foreach ($locations as $location) {
            foreach ($apis as $api) {
                foreach ($types as $type) {
                    // Fetch weather data from Open Meteo
                    $weatherData = $this->openMeteoService->fetchWeatherData($location->id, $type);
                    // Fetch weather data from WeatherApi
                    $weatherApiData = $this->weatherApiService->fetchWeatherData($location->id, $type);

                    if ($type === 'hourly') {
                        $this->openMeteoService->saveHourlyWeatherData($weatherData, $location->id, $api->id, $type);
                        $this->weatherApiService->saveHourlyWeatherData($weatherApiData, $location->id, $api->id, $type);
                    } elseif ($type === 'daily') {
                        $this->openMeteoService->saveDailyWeatherData($weatherData, $location->id, $api->id, $type);
                        $this->weatherApiService->saveDailyWeatherData($weatherApiData, $location->id, $api->id, $type);
                    }
                }
            }
        }

        $this->info('Weather data fetching and saving completed successfully.');
    }
}

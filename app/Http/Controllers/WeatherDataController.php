<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenMeteoService;
use App\Services\WeatherApiService;
use App\Models\WeatherApi;

class WeatherDataController extends Controller
{
    public function weatherDetails(Request $request,$id)
    {
        $openMeteoService = new OpenMeteoService();
        $weatherApiService = new WeatherApiService();
        $types = explode(',', env('FOREST_TYPE', 'hourly,daily'));

        $apis = WeatherApi::all();
        foreach ($apis as $api) {
            foreach ($types as $type) {
                $weatherData = $openMeteoService->fetchWeatherData($id, $type);
                $weatherApiData = $weatherApiService->fetchWeatherData($id, $type);
                if ($type === 'hourly') {
                    $openMeteoService->saveHourlyWeatherData($weatherData, $id, $api->id, $type);
                    $weatherApiService->saveHourlyWeatherData($weatherApiData, $id, $api->id, $type);
                } elseif ($type === 'daily') {
                    $openMeteoService->saveDailyWeatherData($weatherData, $id, $api->id, $type);
                    $weatherApiService->saveDailyWeatherData($weatherApiData, $id, $api->id, $type);
                }
            }
        }

    
        return response()->json($weatherData);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenMeteoService;
use App\Models\WeatherApi;

class WeatherDataController extends Controller
{
    public function weatherDetails(Request $request,$id)
    {
        $openMeteoService = new OpenMeteoService();
        $types = explode(',', env('FOREST_TYPE', 'hourly,daily'));

        $apis = WeatherApi::all();
        foreach ($apis as $api) {
            foreach ($types as $type) {
                $weatherData = $openMeteoService->fetchWeatherData($id, $type);
                if ($type === 'hourly') {
                    $openMeteoService->saveHourlyWeatherData($weatherData, $id, 1, $type);
                    
                } elseif ($type === 'daily') {
                    $openMeteoService->saveDailyWeatherData($weatherData, $id, 1, $type);
                }
            }
        }

    
        return response()->json($weatherData);
    }
}

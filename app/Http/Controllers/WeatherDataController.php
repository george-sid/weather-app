<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenMeteoService;

class WeatherDataController extends Controller
{
    public function weatherDetails(Request $request,$id)
    {
        $openMeteoService = app(OpenMeteoService::class);
        $types = explode(',', env('FOREST_TYPE', 'hourly,daily'));
        foreach($types as $type){
            $weatherData = $openMeteoService->fetchWeatherData($id,$type);
        }
    
        return response()->json($weatherData);
    }
}

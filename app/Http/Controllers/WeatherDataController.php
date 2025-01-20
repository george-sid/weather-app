<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OpenMeteoService;
use App\Services\WeatherApiService;
use App\Models\WeatherApi;
use App\Models\WeatherApiData;
use Illuminate\Support\Facades\Blade;
use App\View\Components\ShowWeatherData;

class WeatherDataController extends Controller
{
    public function weatherDetails(Request $request,$id)
    {
        //call the 2 services for each api
        $openMeteoService = new OpenMeteoService();
        $weatherApiService = new WeatherApiService();
        $types = explode(',', env('FOREST_TYPE', 'hourly,daily'));

        $apis = WeatherApi::all();
        //for each api and for each type daily hourly you call functions to get the data and store to database
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
        //get all data and show them in homepage with component
        $weatherDataValues = WeatherApiData::where('location_id',$id)->with(['weatherApi', 'location'])->get();
        return Blade::renderComponent(new ShowWeatherData([
            'values' => $weatherDataValues
        ]));

    }
}

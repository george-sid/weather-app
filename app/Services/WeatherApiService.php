<?php 

namespace App\Services;

use App\Contracts\WeatherServiceInterface;
use App\Models\Location;
use App\Models\WeatherApiData;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WeatherApiService implements WeatherServiceInterface
{
    public function fetchWeatherData(int $locationId, string $forecastType): array
    {
        // Prepare the API URL for weather data
        $location = Location::find($locationId);
        $latitude = $location->latitude;
        $longitude = $location->longitude;
        $url = "http://api.weatherapi.com/v1/forecast.json";

        $client = new Client();
        $response = $client->get($url, [
            'query' => [
                'key' =>env('WEATHER_API',"9dece841dfba4f0db75145353251901"),
                'q' => "{$latitude},{$longitude}",
                'days' => 4,
            ],
        ]);

        // Parse the response to get weather data
        $data = json_decode($response->getBody(), true);
        return $data;
    }

    public function saveHourlyWeatherData(array $data, int $locationId, int $apiType, string $forecastType): void
    {
        dd($locationId);
    }
    
    public function saveDailyWeatherData(array $data, int $locationId, int $apiType, string $forecastType): void
    {
        dd($locationId);
    }
    
    
    
}

<?php 

namespace App\Services;

use App\Contracts\WeatherServiceInterface;
use App\Models\Location;
use App\Models\WeatherApiData;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

//this is service to for weather meteto to fetch data from api and store in database
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
        foreach ($data['forecast']['forecastday'] as $day) {
            $date = $day['date']; 
            $hourlyTimes = [];
            $hourlyTemperatures = [];
            $hourlyPrecipitations = [];
    
            foreach ($day['hour'] as $hourData) {
                $time = date('H:i', strtotime($hourData['time']));
                $hourlyTimes[] = $time;
                $hourlyTemperatures[] = $hourData['temp_c'];
                $hourlyPrecipitations[] = $hourData['precip_mm'];
            }
    
            // Prepare the hourly data as JSON
            $hourlyData = [
                'location_id' => $locationId,
                'weather_api_id' => $apiType,
                'date' => $date,
                'time' => $hourlyTimes,
                'temperature' => $hourlyTemperatures,
                'precipitation' => $hourlyPrecipitations,
                'temperature_unit' => "°C",
                'step' => $forecastType,
            ];
    
            // Check if there's existing data for this date and location
            $existingRecord = WeatherApiData::where('location_id', $locationId)
                ->where('date', $date)
                ->where('weather_api_id',$apiType)
                ->where('step', $forecastType)
                ->first();
    
            // Update existing record or create a new one
            if ($existingRecord) {
                $existingRecord->update($hourlyData);
            } else {
                WeatherApiData::create($hourlyData);
            }
        }
    }
    

    public function saveDailyWeatherData(array $data, int $locationId, int $apiType, string $forecastType): void
    {
        foreach ($data['forecast']['forecastday'] as $day) {
            $date = $day['date']; 
            $time = '00:00 - 23:59';
            // Prepare the hourly data as JSON
            $hourlyData = [
                'location_id' => $locationId,
                'weather_api_id' => $apiType,
                'date' => $date,
                'time' => [$time],
                'temperature' => [$day['day']['avgtemp_c']],
                'precipitation' => [$day['day']['totalprecip_mm']],
                'temperature_unit' => "°C",
                'step' => $forecastType,
            ];
    
            // Check if there's existing data for this date and location
            $existingRecord = WeatherApiData::where('location_id', $locationId)
                ->where('date', $date)
                ->where('weather_api_id',$apiType)
                ->where('step', $forecastType)
                ->first();
    
            // Update existing record or create a new one
            if ($existingRecord) {
                $existingRecord->update($hourlyData);
            } else {
                WeatherApiData::create($hourlyData);
            }
        }
    }
    
    
    
}

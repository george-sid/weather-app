<?php

namespace App\Services;

use App\Contracts\WeatherServiceInterface;
use App\Models\Location;
use App\Models\WeatherData;
use GuzzleHttp\Client;
use Carbon\Carbon;

class OpenMeteoService implements WeatherServiceInterface
{
    public function fetchWeatherData(int $locationId, string $forecastType): array
    {
        $location = Location::find($locationId);
        $latitude = $location->latitude;
        $longitude = $location->longitude;
        $startDate = Carbon::now()->format('Y-m-d');
        $endDate = Carbon::now()->addDays(3)->format('Y-m-d');

        $client = new Client();
        $response = $client->request('GET', 'https://api.open-meteo.com/v1/forecast', [
            'query' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
                $forecastType => $forecastType === 'hourly' ? 'temperature_2m,precipitation' : 'weather_code,precipitation_sum',  // Hourly or daily data
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
        
        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);

        return $data;
    }

    public function saveWeatherData(array $data, int $locationId): void
    {
    }

}

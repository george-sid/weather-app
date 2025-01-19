<?php 

namespace App\Services;

use App\Contracts\WeatherServiceInterface;
use App\Models\Location;
use App\Models\WeatherApiData;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
                $forecastType => $forecastType === 'hourly' ? 'temperature_2m,precipitation' : 'temperature_2m_max,temperature_2m_min,precipitation_sum',
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
        ]);
        
        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);

        return $data;
    }

    public function saveHourlyWeatherData(array $data, int $locationId, int $apiType, string $forecastType): void
    {
        foreach ($data['hourly']['time'] as $index => $time) {
            $date = (new \DateTime($time))->format('Y-m-d');
    
            // Check if the record for this date and forecast type already exists
            $existingRecord = WeatherApiData::where('location_id', $locationId)
                ->where('date', $date)
                ->where('step', $forecastType)
                ->first();
    
            // Prepare the hourly data arrays (time, temperature, and precipitation)
            $times = [];
            $temperatures = [];
            $precipitations = [];
    
            // Loop through all the hours and append corresponding data for each date
            foreach ($data['hourly']['time'] as $subIndex => $subTime) {
                if ((new \DateTime($subTime))->format('Y-m-d') === $date) {
                    $times[] = (new \DateTime($subTime))->format('H:i'); // Extract time in H:i format
                    $temperatures[] = round($data['hourly']['temperature_2m'][$subIndex], 1); // Round the temperature
                    $precipitations[] = round($data['hourly']['precipitation'][$subIndex], 1); // Round the precipitation
                }
            }
    
            // Prepare the hourly data to be saved
            $hourlyData = [
                'location_id' => $locationId,
                'weather_api_id' => $apiType,
                'date' => $date,
                'time' => $times,
                'step' => $forecastType,
                'temperature' => $temperatures,
                'temperature_unit' => $data['hourly_units']['temperature_2m'],
                'precipitation' => $precipitations,
            ];
    
            //check if exists then update else create
            if ($existingRecord) {
                $existingRecord->update($hourlyData);
            } else {
                WeatherApiData::create($hourlyData);
            }
        }
    }
    
    public function saveDailyWeatherData(array $data, int $locationId, int $apiType, string $forecastType): void
    {
        foreach ($data['daily']['time'] as $index => $date) {
            $temperaturesMax = $data['daily']['temperature_2m_max'][$index];
            $temperaturesMin = $data['daily']['temperature_2m_min'][$index];
            $precipitation = $data['daily']['precipitation_sum'][$index];
        
            $averageTemperature = ($temperaturesMax + $temperaturesMin) / 2;
            $time = '00:00 - 23:59';
        
            // We now need to check if there's existing data for this location and date
            $existingRecord = WeatherApiData::where('location_id', $locationId)
                ->where('date', $date)
                ->where('step', $forecastType)
                ->first();
            $dailyData = [
                'location_id' => $locationId,
                'weather_api_id' => $apiType,
                'date' => $date,
                'time' => [$time],
                'step' => $forecastType,
                'temperature' => [round($averageTemperature,1)],
                'temperature_unit' => $data['daily_units']['temperature_2m_max'],
                'precipitation' => [$precipitation],
            ];
    
            if ($existingRecord) {
                $existingRecord->update($dailyData);
            } else {
                WeatherApiData::create($dailyData);
            }
        }
    }
    
    
    
}

<?php
namespace App\Contracts;

interface WeatherServiceInterface
{
    // function that fetch data from api
    public function fetchWeatherData(int $locationId, string $forecastType): array;
    // function that saves data hoyrly from api
    public function saveHourlyWeatherData(array $data, int $locationId,int $apiType, string $forecastType): void;
    // function that saves data dailty from api
    public function saveDailyWeatherData(array $data, int $locationId,int $apiType, string $forecastType): void;
}
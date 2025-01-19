<?php
namespace App\Contracts;

interface WeatherServiceInterface
{
    public function fetchWeatherData(int $locationId, string $forecastType): array;

    public function saveHourlyWeatherData(array $data, int $locationId,int $apiType, string $forecastType): void;
    public function saveDailyWeatherData(array $data, int $locationId,int $apiType, string $forecastType): void;
}
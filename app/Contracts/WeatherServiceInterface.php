<?php
namespace App\Contracts;

interface WeatherServiceInterface
{
    public function fetchWeatherData(int $locationId, string $forecastType): array;

    public function saveWeatherData(array $data, int $locationId,int $apiType): void;
}
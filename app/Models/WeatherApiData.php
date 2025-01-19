<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherApiData extends Model
{
    use HasFactory;

    protected $table = 'weather_api_data';

    protected $fillable = [
        'location_id',
        'weather_api_id',
        'date',
        'step',
        'time',
        'temperature',
        'precipitation',
        'weather_code',
        'temperature_unit',
    ];

    protected $casts = [
        'time' => 'array',
        'temperature' => 'array',
        'precipitation' => 'array',
        'weather_code' => 'array',
    ];

    public function weatherApi()
    {
        return $this->belongsTo(WeatherApi::class, 'weather_api_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}

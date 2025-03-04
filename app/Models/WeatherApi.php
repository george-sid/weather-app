<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherApi extends Model
{
    use HasFactory;

    protected $table = 'weather_apis';

    protected $fillable = [
        'name',
        'website_url',
    ];
    
}

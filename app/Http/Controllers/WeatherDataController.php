<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WeatherDataController extends Controller
{
    public function weatherDetails(Request $request,$id)
    {
        dd($id);
    }
}

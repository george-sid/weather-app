<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Location;
use GuzzleHttp\Client;
use App\Http\Requests\StoreLocationRequest;

class LocationController extends Controller
{
    public function store(StoreLocationRequest $request)
    {
        // Get the latitude and longitude from OpenStreetMap
        $latLong = $this->findCityLatLong($request->name);
        if ($latLong) {
            $existingLocation = Location::where('latitude', $latLong['latitude'])
                ->where('longitude', $latLong['longitude'])
                ->first();
    
            if ($existingLocation) {
                return response()->json($existingLocation);
            }
    
            // Create a new location
            $location = Location::create([
                'name' => $request->name,
                'latitude' => $latLong['latitude'],
                'longitude' => $latLong['longitude'],
            ]);
            
            return response()->json($location);
        }else{
            return response()->json(['error' => 'Location not found'], 404);
        }

    }
    
    private function findCityLatLong($city)
    {
        $client = new Client();
        $url = 'https://nominatim.openstreetmap.org/search';
    
        try {
            // Send GET request to Nominatim API using Guzzle
            $response = $client->get($url, [
                'query' => [
                    'q' => $city,
                    'format' => 'json',
                    'limit' => 1,
                ],
                'headers' => [
                  'User-Agent' => 'WeatherApp/1.0'
                ]
            ]);

            $data = json_decode($response->getBody(), true); 
            
            if (isset($data[0]) && $data[0]['addresstype'] === 'city' && isset($data[0]['lat']) && isset($data[0]['lon'])) {
                return [
                    'latitude' => (float) $data[0]['lat'],  // Convert lat to float
                    'longitude' => (float) $data[0]['lon'],  // Convert lon to float
                ];
            }

            return false;
    
        } catch (\Exception $e) {
            return false;
        }
    }
    
}

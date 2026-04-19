<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class GoogleMapsService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.google_maps.key');
    }

    /**
     * Geocode an address to Lat/Lng
     */
    public function geocode($address)
    {
        $cacheKey = 'geocode_' . md5($address);
        
        return Cache::remember($cacheKey, now()->addDays(30), function () use ($address) {
            try {
                $response = $this->client->get('https://maps.googleapis.com/maps/api/geocode/json', [
                    'query' => [
                        'address' => $address,
                        'key' => $this->apiKey,
                    ]
                ]);

                $data = json_decode($response->getBody(), true);

                if ($data['status'] === 'OK') {
                    return [
                        'lat' => $data['results'][0]['geometry']['location']['lat'],
                        'lng' => $data['results'][0]['geometry']['location']['lng'],
                        'formatted_address' => $data['results'][0]['formatted_address'],
                    ];
                }

                Log::error("Geocoding failed for address: {$address}", $data);
                return null;
            } catch (\Exception $e) {
                Log::error("Geocoding exception: " . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Get optimized route for a list of waypoints
     * @param string $origin Starting point (lat,lng or address)
     * @param array $waypoints Array of waypoints (lat,lng or address)
     * @param string $destination Ending point (defaults to origin if not provided)
     */
    public function getOptimizedRoute($origin, array $waypoints, $destination = null)
    {
        if (empty($waypoints)) {
            return null;
        }

        $destination = $destination ?: $origin;

        try {
            $query = [
                'origin' => $origin,
                'destination' => $destination,
                'waypoints' => 'optimize:true|' . implode('|', $waypoints),
                'mode' => 'driving',
                'key' => $this->apiKey,
            ];

            $response = $this->client->get('https://maps.googleapis.com/maps/api/directions/json', [
                'query' => $query
            ]);

            $data = json_decode($response->getBody(), true);

            if ($data['status'] === 'OK') {
                return $this->parseDirectionsResponse($data);
            }

            Log::error("Directions API failed", $data);
            return $this->getFallbackRoute($origin, $waypoints);
        } catch (\Exception $e) {
            Log::error("Directions API exception: " . $e->getMessage());
            return $this->getFallbackRoute($origin, $waypoints);
        }
    }

    /**
     * Fallback optimization using Nearest Neighbor algorithm (Distance based)
     */
    protected function getFallbackRoute($origin, array $waypoints)
    {
        Log::info("Using fallback route optimization (Nearest Neighbor)");
        
        $originCoords = $this->parseCoords($origin);
        if (!$originCoords) return null;

        $unvisited = [];
        foreach ($waypoints as $index => $wp) {
            $coords = $this->parseCoords($wp);
            if ($coords) {
                $unvisited[] = ['index' => $index, 'lat' => $coords['lat'], 'lng' => $coords['lng']];
            }
        }

        $currentLat = $originCoords['lat'];
        $currentLng = $originCoords['lng'];
        $optimizedOrder = [];
        
        while (!empty($unvisited)) {
            $nearestIndex = -1;
            $minDist = PHP_FLOAT_MAX;

            foreach ($unvisited as $i => $target) {
                // simple Euclidean distance for fallback
                $dist = sqrt(pow($target['lat'] - $currentLat, 2) + pow($target['lng'] - $currentLng, 2));
                if ($dist < $minDist) {
                    $minDist = $dist;
                    $nearestIndex = $i;
                }
            }

            if ($nearestIndex !== -1) {
                $target = $unvisited[$nearestIndex];
                $optimizedOrder[] = $target['index'];
                $currentLat = $target['lat'];
                $currentLng = $target['lng'];
                array_splice($unvisited, $nearestIndex, 1);
            }
        }

        return [
            'waypoint_order' => $optimizedOrder,
            'total_distance' => count($optimizedOrder) * 2.5, // Mocked 2.5km per stop
            'total_duration' => count($optimizedOrder) * 10,   // Mocked 10m per stop
            'polyline' => '', // Empty polyline for fallback
            'legs' => [],
            'is_fallback' => true
        ];
    }

    protected function parseCoords($str)
    {
        if (preg_match('/^([-+]?\d+\.\d+),([-+]?\d+\.\d+)$/', $str, $matches)) {
            return ['lat' => (float)$matches[1], 'lng' => (float)$matches[2]];
        }
        // If it's an address, we'd need to geocode it first, but for fallback we skip
        return null;
    }

    /**
     * Parse the Directions API response to extract optimized order and route details
     */
    protected function parseDirectionsResponse($data)
    {
        $route = $data['routes'][0];
        $waypointOrder = $route['waypoint_order']; // This tells us the optimized sequence
        
        $totalDistance = 0;
        $totalDuration = 0;

        foreach ($route['legs'] as $leg) {
            $totalDistance += $leg['distance']['value'];
            $totalDuration += $leg['duration']['value'];
        }

        return [
            'waypoint_order' => $waypointOrder,
            'total_distance' => $totalDistance / 1000, // km
            'total_duration' => round($totalDuration / 60), // minutes
            'polyline' => $route['overview_polyline']['points'],
            'legs' => $route['legs'],
        ];
    }
}

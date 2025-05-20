<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Ride;
use App\Exceptions\DriverException;
use Illuminate\Http\JsonResponse;

class DriverController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->all();
        $car = $data['car'] ?? [];

        $driver = Driver::create([
            'name' => $data['name'],
            'car_license_plate' => $car['license_plate'] ?? null,
            'car_model' => $car['model'] ?? null,
            'car_color' => $car['color'] ?? null,
            'available' => $data['available'] ?? true,
        ]);

        return response()->json([
            'id' => $driver->id,
            'name' => $driver->name,
            'car' => [
                'license_plate' => $driver->car_license_plate,
                'model' => $driver->car_model,
                'color' => $driver->car_color
            ],
            'available' => $driver->available,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $driver = Driver::findOrFail($id);
        $driver->delete();

        return response()->json(null, 204);
    }

    public function getOpenRides($id): JsonResponse
    {
        $driver = Driver::findOrFail($id);
        $rides = $driver->getOpenRides();

        if ($rides->isEmpty()) {
            return response()->json([
                'message' => 'No rides waiting to be accepted',
            ], 404);
        }

        return response()->json($rides->map(function ($ride) {
            return [
                'id' => $ride->id,
                'status' => $ride->status,
                'drop_off' => $ride->drop_off,
                'pick_up' => $ride->pick_up,
                'passenger' => [
                    'name' => $ride->passenger_name,
                    'email' => $ride->passenger_email
                ]
            ];
        }));
    }
}

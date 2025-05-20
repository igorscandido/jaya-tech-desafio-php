<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ride;
use App\Models\Driver;
use Illuminate\Http\JsonResponse;

class RideController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ride = Ride::with('driver')->findOrFail($id);

        return response()->json([
            'id' => $ride->id,
            'status' => $ride->status,
            'pick_up' => $ride->pick_up,
            'drop_off' => $ride->drop_off,
            'driver' => $ride->driver ? [
                'name' => $ride->driver->name,
                'car' => [
                    'license_plate' => $ride->driver->car_license_plate,
                    'model' => $ride->driver->car_model,
                    'color' => $ride->driver->car_color
                ]
            ] : null
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ride = Ride::findOrFail($id);
        $ride->delete();
        return response()->noContent();
    }

    public function requestDriver(Request $request): JsonResponse
    {
        $fields = $request->all();

        $driver = Driver::where('available', true)
            ->orderBy('activation_date', 'asc')
            ->first();

        if (!$driver) {
            return response()->json(['error' => 'We do not have drivers available'], 400);
        }

        $ride = Ride::create([
            'passenger_name' => $fields['passenger']['name'],
            'passenger_email' => $fields['passenger']['email'],
            'pick_up' => $fields['pick_up'],
            'drop_off' => $fields['drop_off'],
            'driver_id' => $driver->id
        ]);

        $ride->request();

        return response()->json([
            'id' => $ride->id,
            'status' => $ride->status,
            'drop_off' => $ride->drop_off,
            'pick_up' => $ride->pick_up,
            'driver' => [
                'name' => $driver->name,
                'car' => [
                    'color' => $driver->car_color,
                    'license_plate' => $driver->car_license_plate,
                    'model' => $driver->car_model
                ]
            ]
        ]);
    }

    public function cancelRide(Request $request): JsonResponse
    {
        $ride = Ride::findOrFail($request->id);
        $ride->cancel();

        return response()->json([
            'id' => $ride->id,
            'status' => $ride->status,
            'drop_off' => $ride->drop_off,
            'pick_up' => $ride->pick_up
        ]);
    }

    public function acceptRide(Request $request): JsonResponse
    {
        $ride = Ride::findOrFail($request->id);
        $driver = Driver::findOrFail($ride->driver_id);
        $ride->accept($driver);

        return response()->json([
            'id' => $ride->id,
            'status' => $ride->status,
            'drop_off' => $ride->drop_off,
            'pick_up' => $ride->pick_up,
            'passenger' => [
                'name' => $ride->passenger_name,
                'email' => $ride->passenger_email
            ]
        ]);
    }

    public function refuseRide(Request $request): JsonResponse
    {
        $ride = Ride::findOrFail($request->id);
        $ride->refuse();

        return response()->json([
            'id' => $ride->id,
            'status' => $ride->status,
            'drop_off' => $ride->drop_off,
            'pick_up' => $ride->pick_up,
            'passenger' => [
                'name' => $ride->passenger_name,
                'email' => $ride->passenger_email
            ]
        ]);
    }

    public function finishRide(Request $request): JsonResponse
    {
        $ride = Ride::findOrFail($request->id);
        $ride->finish();
        $ride->price = $request->price;
        $ride->save();

        return response()->json([
            'id' => $ride->id,
            'status' => $ride->status,
            'drop_off' => $ride->drop_off,
            'price' => $ride->price,
            'passenger' => [
                'name' => $ride->passenger_name,
                'email' => $ride->passenger_email
            ]
        ]);
    }

    public function getOpenRides($driverId): JsonResponse
    {
        $driver = Driver::findOrFail($driverId);
        
        if (!$driver->available) {
            return response()->json([
                'message' => 'Driver is not available',
            ], 400);
        }

        $rides = Ride::where('status', Ride::STATUS_REQUESTED)
            ->whereNull('driver_id')
            ->get();

        if ($rides->isEmpty()) {
            return response()->json([
                'message' => 'No rides waiting to be accepted',
            ], 404);
        }

        return response()->json($rides->map(function ($ride) {
            return [
                'id' => $ride->id,
                'passenger_name' => $ride->passenger_name,
                'passenger_email' => $ride->passenger_email,
                'pick_up' => $ride->pick_up,
                'drop_off' => $ride->drop_off,
                'status' => $ride->status,
            ];
        }));
    }
}

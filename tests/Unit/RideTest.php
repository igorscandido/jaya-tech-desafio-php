<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Ride;
use App\Models\Driver;
use App\Exceptions\RideException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RideTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_accept_ride_when_driver_is_available()
    {
        $driver = Driver::create([
            'name' => 'John Doe',
            'car_license_plate' => 'ABC123',
            'car_model' => 'Toyota Corolla',
            'car_color' => 'Blue',
            'available' => true
        ]);

        $ride = Ride::create([
            'passenger_name' => 'Jane Smith',
            'passenger_email' => 'jane@example.com',
            'pick_up' => '123 Main St',
            'drop_off' => '456 Park Ave',
            'status' => Ride::STATUS_REQUESTED
        ]);

        $ride->accept($driver);

        $this->assertEquals(Ride::STATUS_ACCEPTED, $ride->status);
        $this->assertEquals($driver->id, $ride->driver_id);
        $this->assertFalse($driver->available);
    }

    public function test_cannot_accept_ride_when_driver_is_unavailable()
    {
        $driver = Driver::create([
            'name' => 'John Doe',
            'car_license_plate' => 'ABC123',
            'car_model' => 'Toyota Corolla',
            'car_color' => 'Blue',
            'available' => false
        ]);

        $ride = Ride::create([
            'passenger_name' => 'Jane Smith',
            'passenger_email' => 'jane@example.com',
            'pick_up' => '123 Main St',
            'drop_off' => '456 Park Ave',
            'status' => Ride::STATUS_REQUESTED
        ]);

        $this->expectException(RideException::class);
        $this->expectExceptionMessage('Driver is not available');

        $ride->accept($driver);
    }
} 
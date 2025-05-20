<?php

namespace App\Models;

use App\Exceptions\DriverException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'car_license_plate',
        'car_model',
        'car_color',
        'available',
    ];

    protected $casts = [
        'available' => 'boolean',
    ];

    public function rides()
    {
        return $this->hasMany(Ride::class);
    }

    public function becomeAvailable()
    {
        $this->available = true;
        $this->save();
    }

    public function becomeBusy()
    {
        $this->available = false;
        $this->save();
    }

    public function getOpenRides()
    {
        return $this->rides()
            ->where('status', Ride::STATUS_REQUESTED)
            ->get();
    }
}

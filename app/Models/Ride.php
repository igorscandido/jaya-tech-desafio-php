<?php

namespace App\Models;

use App\Exceptions\RideException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    use HasFactory;

    protected $fillable = [
        'passenger_name',
        'passenger_email',
        'driver_id',
        'status',
        'pick_up',
        'drop_off',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    const STATUS_REQUESTED = 'REQUESTED';
    const STATUS_ACCEPTED = 'ACCEPTED';
    const STATUS_FINISHED = 'FINISHED';
    const STATUS_CANCELLED = 'CANCELLED';
    const STATUS_REFUSED = 'REFUSED';

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function request()
    {
        if ($this->status !== null) {
            throw RideException::invalidState('Ride already has a status');
        }

        $this->status = self::STATUS_REQUESTED;
        $this->save();
    }

    public function accept(Driver $driver)
    {
        if ($this->status !== self::STATUS_REQUESTED) {
            throw RideException::invalidState('Ride must be in REQUESTED state to be accepted');
        }

        if (!$driver->available) {
            throw RideException::invalidState('Driver is not available');
        }

        $this->driver_id = $driver->id;
        $this->status = self::STATUS_ACCEPTED;
        $this->save();

        $driver->available = false;
        $driver->save();
    }

    public function finish()
    {
        if ($this->status !== self::STATUS_ACCEPTED) {
            throw RideException::invalidState('Ride must be in ACCEPTED state to be finished');
        }

        $this->status = self::STATUS_FINISHED;
        $this->save();

        if ($this->driver) {
            $this->driver->available = true;
            $this->driver->save();
        }
    }

    public function cancel()
    {
        if (!in_array($this->status, [self::STATUS_REQUESTED, self::STATUS_ACCEPTED])) {
            throw RideException::invalidState('Ride can only be cancelled in REQUESTED or ACCEPTED state');
        }

        $this->status = self::STATUS_CANCELLED;
        $this->save();

        if ($this->driver) {
            $this->driver->available = true;
            $this->driver->save();
        }
    }

    public function refuse()
    {
        if ($this->status !== self::STATUS_REQUESTED) {
            throw RideException::invalidState('Ride must be in REQUESTED state to be refused');
        }

        $this->status = self::STATUS_REFUSED;
        $this->save();
    }
}

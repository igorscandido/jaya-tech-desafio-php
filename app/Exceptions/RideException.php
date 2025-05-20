<?php

namespace App\Exceptions;

use Exception;

class RideException extends Exception
{
    public static function notFound()
    {
        return new self('Ride not found');
    }

    public static function invalidState($message)
    {
        return new self($message);
    }

    public static function noDriversAvailable()
    {
        return new self('We do not have drivers available');
    }
} 
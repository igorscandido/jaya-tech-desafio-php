<?php

namespace App\Exceptions;

use Exception;

class DriverException extends Exception
{
    public static function notFound()
    {
        return new self('Driver not found');
    }
} 
<?php

namespace App\Services\DevTo\Facades;

use App\Services\DevTo\DevToService;
use Illuminate\Support\Facades\Facade;

/**
 * @see DevToService
 */
class DevTo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return DevToService::class;
    }
}

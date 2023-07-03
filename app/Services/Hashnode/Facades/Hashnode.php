<?php

namespace App\Services\Hashnode\Facades;

use App\Services\Hashnode\HashnodeService;
use Illuminate\Support\Facades\Facade;

/**
 * @see HashnodeService
 */
class Hashnode extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return HashnodeService::class;
    }
}

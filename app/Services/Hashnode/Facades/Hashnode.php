<?php

namespace App\Services\Hashnode\Facades;

use App\Services\Hashnode\HashnodeManager;
use Illuminate\Support\Facades\Facade;

/**
 * @see HashnodeManager
 */
class Hashnode extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return HashnodeManager::class;
    }
}

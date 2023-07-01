<?php

namespace App\Services\Medium\Facades;

use App\Domains\Article\DTOs\ArticleData;
use App\Services\Medium\MediumManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void publish(ArticleData $articleData);
 *
 * @see MediumManager
 */
class Medium extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return MediumManager::class;
    }
}

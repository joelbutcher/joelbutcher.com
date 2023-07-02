<?php

namespace App\Services\Twitter\Facades;

use App\Services\Twitter\Contracts\TwitterServiceInterface;
use App\Services\Twitter\DTOs\Profile;
use App\Services\Twitter\DTOs\Tweet;
use App\Services\Twitter\Services\TwitterService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static bool enabled();
 * @method static Profile profile();
 * @method static Tweet sendTweet(string $content);
 * @method static void deleteTweet(string $id);
 *
 * @see TwitterService
 * @see TwitterServiceInterface
 */
class Twitter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TwitterServiceInterface::class;
    }
}

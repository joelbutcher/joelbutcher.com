<?php

namespace App\Providers;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Services\Twitter\Twitter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->when([Twitter::class])
            ->needs(TwitterOAuth::class)
            ->give(function () {
                return new TwitterOAuth(
                    config('services.twitter.consumer_key'),
                    config('services.twitter.consumer_secret'),
                    config('services.twitter.access_token'),
                    config('services.twitter.access_secret')
                );
            });
    }
}

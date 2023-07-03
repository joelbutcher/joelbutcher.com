<?php

namespace App\Services\Twitter;

use App\Http\Integrations\Twitter\TwitterConnector;
use App\Services\Twitter\Contracts\TwitterServiceInterface;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class TwitterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerTwitterService();
    }

    private function registerTwitterService(): void
    {
        if (App::isLocal()) {
            $this->app->bind(TwitterServiceInterface::class, LoggingTwitterService::class);
            $this->app->alias(TwitterServiceInterface::class, 'twitter');

            return;
        }

        $this->app->bind(TwitterServiceInterface::class, TwitterService::class);
        $this->app->alias(TwitterServiceInterface::class, 'twitter');

        $this->app->bind(
            abstract: TwitterConnector::class,
            concrete: fn () => new TwitterConnector(
                oauth1: new Oauth1([
                    'consumer_key' => config('services.twitter.consumer_key'),
                    'consumer_secret' => config('services.twitter.consumer_secret'),
                    'token' => config('services.twitter.access_token'),
                    'token_secret' => config('services.twitter.access_secret'),
                ])
            )
        );
    }
}

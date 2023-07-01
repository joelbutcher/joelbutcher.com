<?php

namespace App\Services\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Http\Integrations\Twitter\TwitterConnector;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class TwitterServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            abstract: TwitterOAuth::class,
            concrete: fn (Application $app) => new TwitterOAuth(
                consumerKey: config('services.twitter.consumer_key', ''),
                consumerSecret: config('services.twitter.consumer_secret', ''),
                oauthToken: config('services.twitter.access_token', ''),
                oauthTokenSecret: config('services.twitter.access_secret', ''),
            ),
        );

        $this->app->alias(TwitterManager::class, 'twitter');

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

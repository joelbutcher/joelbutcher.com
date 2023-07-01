<?php

use Abraham\TwitterOAuth\HmacSha1;
use Abraham\TwitterOAuth\Request;
use App\Http\Integrations\Twitter\Requests\GetProfileRequest;
use App\Http\Integrations\Twitter\TwitterConnector;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Tests\TestCase;

uses(TestCase::class);

it('builds the correct authorization header value', closure: function () {
    $url = 'https://api.twitter.com/2/users/me';

    $twitterRequest = Request::fromConsumerAndToken(
        consumer: $consumer = new \Abraham\TwitterOAuth\Consumer(
            key: config('services.twitter.consumer_key'),
            secret: config('services.twitter.consumer_secret'),
        ),
        token: $token = new \Abraham\TwitterOAuth\Token(
            key: config('services.twitter.access_token'),
            secret: config('services.twitter.access_secret'),
        ),
        httpMethod: 'GET',
        httpUrl: $url,
        parameters: $parameters = [],
        json: $json = true,
    );

    $twitterRequest->signRequest(
        new HmacSha1(),
        $consumer,
        $token
    );

    $expected = $twitterRequest->toHeader();
    $expected = str($expected)->after('Authorization: ')->explode(', ')->toArray();

    $connector = new TwitterConnector(
        oauth1: new Oauth1([
            'consumer_key' => config('services.twitter.consumer_key'),
            'consumer_secret' => config('services.twitter.consumer_secret'),
            'token' => config('services.twitter.access_token'),
            'token_secret' => config('services.twitter.access_secret'),
        ])
    );

    $reflector = new ReflectionClass(TwitterConnector::class);
    $method = $reflector->getMethod('buildAuthorizationHeader');
    $method->setAccessible(true);

    $header = $method->invoke($connector, new GetProfileRequest());

    expect(explode(', ', $header))->toEqual($expected);
});

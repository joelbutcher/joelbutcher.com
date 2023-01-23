<?php

namespace App\Services\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Services\Twitter\Exceptions\TwitterException;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Log;

class Twitter
{
    public function __construct(
        private readonly TwitterOAuth $twitter,
        private readonly Repository $config,
    ) {
    }

    public function post(TwitterStatusUpdate $status): array
    {
        if (! $this->config->get('services.twitter.enabled')) {
            Log::debug('Tweet sent!', [
                'path' => $status->apiPath(),
                'parameters' => $status->requestBody(),
            ]);

            return [];
        }

        $response = $this->twitter->post(
            path: $status->apiPath(),
            parameters: $status->requestBody(),
        );

        if ($this->twitter->getLastHttpCode() !== 200) {
            throw new TwitterException(json_encode($this->twitter->getLastBody()));
        }

        return (array) $response;
    }

    public function removeTweet(TwitterRemoveTweet $removeTweet): bool
    {
        $path = rtrim($removeTweet->apiPath(), '/');

        if (! $this->config->get('services.twitter.enabled')) {
            Log::debug('Tweet deleted!', [
                'path' => $path,
            ]);

            return true;
        }

        $this->twitter->post("$path/$removeTweet->tweetId");

        if ($this->twitter->getLastHttpCode() !== 200) {
            throw new TwitterException(json_encode($this->twitter->getLastBody()));
        }

        return true;
    }
}

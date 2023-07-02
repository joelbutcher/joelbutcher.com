<?php

namespace App\Services\Twitter\Services;

use App\Http\Integrations\Twitter\Requests\DeleteTweetRequest;
use App\Http\Integrations\Twitter\Requests\GetProfileRequest;
use App\Http\Integrations\Twitter\Requests\SendTweetRequest;
use App\Http\Integrations\Twitter\Responses\GetProfileResponse;
use App\Http\Integrations\Twitter\Responses\SendTweetResponse;
use App\Http\Integrations\Twitter\TwitterConnector;
use App\Services\Twitter\Contracts\TwitterServiceInterface;
use App\Services\Twitter\DTOs\Profile;
use App\Services\Twitter\DTOs\Tweet;

readonly class TwitterService implements TwitterServiceInterface
{
    public function __construct(
        private TwitterConnector $connector
    ) {
    }

    public function enabled(): bool
    {
        return config('services.twitter.enabled');
    }

    public function profile(): Profile
    {
        /** @var GetProfileResponse $response */
        $response = $this->connector->send(
            request: GetProfileRequest::make(),
        );

        return Profile::fromResponse($response);
    }

    public function sendTweet(string $content): Tweet
    {
        /** @var SendTweetResponse $response */
        $response = $this->connector->send(
            request: new SendTweetRequest(
                content: $content,
            ),
        );

        return Tweet::fromResponse($response);
    }

    public function deleteTweet(Tweet $tweet): bool
    {
        return $this->connector->send(
            request: DeleteTweetRequest::make(
                tweet: $tweet,
            ),
        )->successful();
    }
}

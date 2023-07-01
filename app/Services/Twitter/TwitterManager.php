<?php

namespace App\Services\Twitter;

use App\Http\Integrations\Twitter\Requests\DeleteTweetRequest;
use App\Http\Integrations\Twitter\Requests\GetProfileRequest;
use App\Http\Integrations\Twitter\Requests\SendTweetRequest;
use App\Http\Integrations\Twitter\Responses\GetProfileResponse;
use App\Http\Integrations\Twitter\Responses\SendTweetResponse;
use App\Http\Integrations\Twitter\TwitterConnector;
use App\Services\Twitter\DTOs\Profile;
use App\Services\Twitter\DTOs\Tweet;
use Illuminate\Support\Arr;
use RuntimeException;

readonly class TwitterManager
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

    private function handleAnyErrors(): void
    {
        $body = $this->body();

        $title = Arr::get($body, 'title');
        $errorDetail = Arr::get($body, 'detail');

        throw new RuntimeException(
            message: "Twitter API error [$title]: $errorDetail",
            code: $this->status(),
        );
    }

    private function successful(): bool
    {
        return $this->status() >= 200 && $this->status() < 300;
    }

    private function status(): int
    {
        return $this->twitter->getLastHttpCode();
    }

    private function body(): array
    {
        return json_decode(
            json: json_encode(
                value: $this->twitter->getLastBody()
            ),
            associative: true,
            flags: JSON_THROW_ON_ERROR,
        );
    }
}

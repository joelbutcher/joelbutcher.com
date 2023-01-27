<?php

namespace App\Services\Twitter\Exceptions;

class TwitterException extends \Exception
{
    public static function requestWasNotSuccessful(mixed $response): self
    {
        if (isset($response->error)) {
            return new self("Couldn't post notification. Response: ".$response->error);
        }

        $responseBody = print_r($response->errors[0]->message, true);

        return new self("Couldn't post tweet: ".$responseBody);
    }

    public static function failedToRemoveTweet(string $tweetId): self
    {
        return new self("Could not remove tweet '$tweetId'");
    }
}

<?php

namespace App\Services\Twitter\Contracts;

use App\Services\Twitter\DTOs\Tweet;
use App\Services\Twitter\DTOs\TwitterProfile;

interface TwitterServiceInterface
{
    public function enabled(): bool;

    public function profile(): TwitterProfile;

    public function sendTweet(string $content): Tweet;

    public function deleteTweet(Tweet $tweet): bool;
}

<?php

namespace App\Services\Twitter\Contracts;

use App\Services\Twitter\DTOs\Profile;
use App\Services\Twitter\DTOs\Tweet;

interface TwitterServiceInterface
{
    public function enabled(): bool;

    public function profile(): Profile;

    public function sendTweet(string $content): Tweet;

    public function deleteTweet(Tweet $tweet): bool;
}

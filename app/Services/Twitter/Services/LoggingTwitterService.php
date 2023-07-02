<?php

namespace App\Services\Twitter\Services;

use App\Services\Twitter\Contracts\TwitterServiceInterface;
use App\Services\Twitter\DTOs\Profile;
use App\Services\Twitter\DTOs\Tweet;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Log\LogManager;

class LoggingTwitterService implements TwitterServiceInterface
{
    private readonly Generator $faker;

    public function __construct(
        private LogManager $logger,
    ) {
        $this->faker = Factory::create();
    }

    public function enabled(): bool
    {
        return true;
    }

    public function profile(): Profile
    {
        return new Profile(
            id: $this->faker->uuid(),
            name: $this->faker->name(),
            username: $this->faker->userName(),
        );
    }

    public function sendTweet(string $content): Tweet
    {
        $this->logger->debug('Tweeting', [
            'content' => $content,
        ]);

        return new Tweet(
            id: $this->faker->uuid(),
            content: $content,
        );
    }

    public function deleteTweet(Tweet $tweet): bool
    {
        return true;
    }
}

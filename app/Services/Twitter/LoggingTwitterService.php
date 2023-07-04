<?php

namespace App\Services\Twitter;

use App\Services\Twitter\Contracts\TwitterServiceInterface;
use App\Services\Twitter\DTOs\Tweet;
use App\Services\Twitter\DTOs\TwitterProfile;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Log\LogManager;

class LoggingTwitterService implements TwitterServiceInterface
{
    private Generator $faker;

    public function __construct(
        private readonly LogManager $logger,
    ) {
        $this->faker = Factory::create();
    }

    public function enabled(): bool
    {
        return true;
    }

    public function profile(): TwitterProfile
    {
        return new TwitterProfile(
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

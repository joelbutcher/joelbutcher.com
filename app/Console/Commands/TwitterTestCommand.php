<?php

namespace App\Console\Commands;

use App\Services\Twitter\Contracts\TwitterServiceInterface;
use Illuminate\Console\Command;

class TwitterTestCommand extends Command
{
    protected $signature = 'twitter:test';

    public function __construct(
        private readonly TwitterServiceInterface $twitter,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $tweet = $this->twitter->sendTweet(
            content: 'Hello world!',
        );

        $this->twitter->deleteTweet(
            tweet: $tweet,
        );

        return self::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use App\Services\Twitter\TwitterManager;
use Illuminate\Console\Command;

class TwitterTestCommand extends Command
{
    protected $signature = 'twitter:test';

    public function __construct(
        private readonly TwitterManager $twitter,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $tweet = $this->twitter->sendTweet(
            content: 'Hello world!',
        );

        dd($tweet);

        $this->twitter->deleteTweet(
            tweet: $tweet,
        );

        return self::SUCCESS;
    }
}

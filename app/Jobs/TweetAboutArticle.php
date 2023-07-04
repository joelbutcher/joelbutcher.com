<?php

namespace App\Jobs;

use App\Domains\Article\Actions\TweetArticle;
use App\Domains\Article\Projections\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TweetAboutArticle implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private readonly Article $article,
    ) {
    }

    public function handle(TweetArticle $tweetArticle): void
    {
        if ($this->article->hasBeenTweeted) {
            return;
        }

        ($tweetArticle)(
            article: $this->article,
        );
    }
}

<?php

namespace App\Jobs;

use App\Domains\Article\Actions\PublishArticle;
use App\Domains\Article\Enums\Platform;
use App\Domains\Article\Projections\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PublishArticleToPlatforms implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param array<array-key, Platform> $platforms
     */
    public function __construct(
        private readonly Article $article,
        private readonly array $platforms,
    ) {
        collect($this->platforms)->each(function ($platform) {
            assert($platform instanceof Platform);
        });
    }

    public function handle(PublishArticle $publishArticle): void
    {
        if ($this->article->hasBeenPublished) {
            return;
        }

        ($publishArticle)(
            $this->article,
            ...$this->platforms,
        );
    }
}

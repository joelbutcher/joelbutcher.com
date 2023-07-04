<?php

namespace App\Domains\Article\Actions;

use App\Domains\Article\DTOs\ArticleData;
use App\Domains\Article\Projections\Article;
use App\Jobs\PublishArticleToPlatforms;
use App\Jobs\TweetAboutArticle;
use Illuminate\Support\Facades\Bus;

final readonly class SaveArticle
{
    public function __construct(
        private CreateArticle $createArticle = new CreateArticle(),
        private UpdateArticle $updateArticle = new UpdateArticle(),
    ) {
    }

    public function __invoke(ArticleData $data): void
    {
        $article = Article::findByUuid($data->uuid)
            ? ($this->updateArticle)(data: $data)
            : ($this->createArticle)(data: $data);

        $jobs = collect();

        if ($data->published) {
            $jobs->push(new PublishArticleToPlatforms(
                article: $article,
                platforms: [...$data->platforms],
            ));
        }

        if ($data->postToTwitter) {
            $jobs->push(new TweetAboutArticle(
                article: $article,
            ));
        }

        Bus::chain($jobs)->dispatch();
    }
}

<?php

namespace App\Domains\Article\Actions;

use App\Domains\Article\DTOs\ArticleData;
use App\Domains\Article\Projections\Article;
use App\Jobs\PublishArticleToPlatforms;
use App\Jobs\TweetAboutArticle;

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

        if (! $data->published) {
            return;
        }

        dispatch(new PublishArticleToPlatforms(
            article: $article,
            platforms: [...$data->platforms],
        ));

        if (! $data->postToTwitter) {
            return;
        }

        dispatch(new TweetAboutArticle(
            article: $article,
        ));
    }
}

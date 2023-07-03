<?php

namespace App\Domains\Article\Actions;

use App\Domains\Article\DTOs\ArticleData;
use App\Domains\Article\Projections\Article;

final readonly class SaveArticle
{
    public function __construct(
        private TweetArticle $tweetArticle,
        private CreateArticle $createArticle = new CreateArticle(),
        private UpdateArticle $updateArticle = new UpdateArticle(),
        private PublishArticle $publishArticle = new PublishArticle(),
    ) {
    }

    public function __invoke(ArticleData $data): void
    {
        // If the article already exists, update it, otherwise create it.
        $article = Article::findByUuid($data->uuid)
            ? ($this->updateArticle)(data: $data)
            : ($this->createArticle)(data: $data);

        // Article has already been published, no need to publish it again.
        if ($article->hasBeenPublished || !$data->published) {
            return;
        }

        // Publish the article.
        ($this->publishArticle)(
            $article,
            ...$data->platforms,
        );

        // Article has already been tweeted, no need to tweet it again.
        if ($article->hasBeenTweeted || !$data->postToTwitter) {
            return;
        }

        // Tweet the article.
        ($this->tweetArticle)(
            article: $article,
        );
    }
}

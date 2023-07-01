<?php

namespace App\Domains\Article\Projectors;

use App\Domains\Article\Events\ArticleWasCreated;
use App\Domains\Article\Events\ArticleWasDeleted;
use App\Domains\Article\Events\ArticleWasPublished;
use App\Domains\Article\Events\ArticleWasTweeted;
use App\Domains\Article\Events\ArticleWasUpdated;
use App\Domains\Article\Projections\Article;
use Carbon\CarbonImmutable;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class ArticleProjector extends Projector
{
    public function onArticleWasCreated(ArticleWasCreated $event): void
    {
        (new Article())->writeable()->create([
            'uuid' => $event->uuid,
            'title' => $event->title,
            'slug' => $event->slug,
            'excerpt' => $event->excerpt,
            'content' => $event->content,
            'tags' => $event->tags,
            'platforms' => $event->platforms,
            'published_at' => $event->published ? CarbonImmutable::now() : null,
            'created_at' => $event->createdAt,
        ]);
    }

    public function onArticleWasUpdated(ArticleWasUpdated $event): void
    {
        $article = Article::findByUuid(uuid: $event->uuid)->writeable();

        $article->update(array_merge([
            'uuid' => $event->uuid,
            'title' => $event->title,
            'slug' => $event->slug,
            'excerpt' => $event->excerpt,
            'content' => $event->content,
            'tags' => $event->tags,
            'platforms' => $event->platforms,
            'updated_at' => $event->updatedAt,
        ], $article->hasBeenPublished() ? [] : [
            'published_at' => $event->published ? CarbonImmutable::now() : null,
        ]));
    }

    public function onArticleWasPublished(ArticleWasPublished $event): void
    {
        $article = Article::findByUuid(uuid: $event->uuid)->writeable();

        $article->update([
            'published_at' => $event->publishedAt,
        ]);
    }

    public function onArticleWasTweeted(ArticleWasTweeted $event): void
    {
        $article = Article::findByUuid(uuid: $event->uuid)->writeable();

        $article->update([
            'tweet' => $event->tweet(),
            'tweeted_at' => $event->tweetedAt,
        ]);
    }

    public function onArticleWasDeleted(ArticleWasDeleted $event): void
    {
        $article = Article::findByUuid(uuid: $event->uuid);

        $article->writeable()->delete();
    }
}

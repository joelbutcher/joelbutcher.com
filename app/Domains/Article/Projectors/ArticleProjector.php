<?php

namespace App\Domains\Article\Projectors;

use App\Domains\Article\Events\ArticleWasCreated;
use App\Domains\Article\Events\ArticleWasDeleted;
use App\Domains\Article\Events\ArticleWasPublished;
use App\Domains\Article\Events\ArticleWasTweeted;
use App\Domains\Article\Events\ArticleWasUpdated;
use App\Domains\Article\Projections\Article;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class ArticleProjector extends Projector
{
    public function onArticleWasCreated(ArticleWasCreated $event): void
    {
        (new Article())->writeable()->create([
            'uuid' => $event->uuid,
            'title' => $event->title,
            'slug' => $event->slug,
            'series' => $event->series,
            'excerpt' => $event->excerpt,
            'image_path' => $event->imagePath,
            'content' => $event->content, 'created_at' => $event->createdAt,
        ]);
    }

    public function onArticleWasUpdated(ArticleWasUpdated $event): void
    {
        Article::findByUuid(uuid: $event->uuid)
            ->writeable()
            ->update(array_merge([
                'uuid' => $event->uuid,
                'title' => $event->title,
                'slug' => $event->slug,
                'series' => $event->series,
                'excerpt' => $event->excerpt,
                'image_path' => $event->imagePath,
                'content' => $event->content,
                'tags' => $event->tags,
                'updated_at' => $event->updatedAt,
            ]));
    }

    public function onArticleWasPublished(ArticleWasPublished $event): void
    {
        Article::findByUuid(uuid: $event->uuid)
            ->writeable()
            ->update([
                'platforms' => array_filter($event->platforms, fn ($platform) => $platform->isEnabled()),
                'published_at' => $event->publishedAt,
            ]);
    }

    public function onArticleWasTweeted(ArticleWasTweeted $event): void
    {
        Article::findByUuid(uuid: $event->uuid)
            ->writeable()
            ->update([
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

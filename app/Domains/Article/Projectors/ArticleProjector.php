<?php

namespace App\Domains\Article\Projectors;

use App\Domains\Article\Events\ArticleWasCreated;
use App\Domains\Article\Events\ArticleWasDeleted;
use App\Domains\Article\Events\ArticleWasShared;
use App\Domains\Article\Events\ArticleWasUpdated;
use App\Domains\Article\Projections\Article;
use App\Services\Twitter\Twitter;
use App\Services\Twitter\TwitterRemoveTweet;
use App\Services\Twitter\TwitterStatusUpdate;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class ArticleProjector extends Projector
{
    public function __construct(private readonly Twitter $twitter)
    {
        //
    }

    public function onArticleWasCreated(ArticleWasCreated $event): void
    {
        (new Article())->writeable()->create([
            'uuid' => $event->articleUuid,
            'title' => $event->title,
            'slug' => $event->slug,
            'featured_image' => $event->featuredImage,
            'excerpt' => $event->excerpt,
            'content' => $event->content,
            'published' => $event->published,
            'created_at' => $event->createdAt,
        ]);
    }

    public function onArticleWasUpdated(ArticleWasUpdated $event): void
    {
        Article::uuid(uuid: $event->articleUuid)
            ->writeable()
            ->update([
                'title' => $event->title,
                'slug' => $event->slug,
                'featured_image' => $event->featuredImage,
                'excerpt' => $event->excerpt,
                'content' => $event->content,
                'published' => $event->published,
                'updated_at' => $event->updatedAt,
            ]);
    }

    public function onArticleWasShared(ArticleWasShared $event): void
    {
        $article = Article::uuid(uuid: $event->articleUuid);

        $response = $this->twitter->post(
            status: TwitterStatusUpdate::forNewArticle(
                article: $article,
            ),
        );

        // @todo save tweet id to article
        $article->writeable()->update([
            'shared_at' => $event->sharedAt,
            'tweet_id' => $response['id'] ?? null,
        ]);
    }

    public function onArticleWasDeleted(ArticleWasDeleted $event): void
    {
        tap(
            value: Article::uuid(uuid: $event->articleUuid),
            callback: function (Article $article) {
                if ($article->tweet_id) {
                    $this->twitter->removeTweet(removeTweet: TwitterRemoveTweet::forArticle($article));
                }

                $article->writeable()->delete();
            },
        );
    }
}

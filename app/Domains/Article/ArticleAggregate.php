<?php

namespace App\Domains\Article;

use App\Domains\Article\DataTransferObjects\ArticleData;
use App\Domains\Article\Events\ArticleWasCreated;
use App\Domains\Article\Events\ArticleWasDeleted;
use App\Domains\Article\Events\ArticleWasShared;
use App\Domains\Article\Events\ArticleWasUpdated;
use App\Domains\Article\Exceptions\ArticleException;
use App\Domains\Article\Partials\ArticleStateMachine;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class ArticleAggregate extends AggregateRoot
{
    public ArticleStateMachine $state;

    public function __construct()
    {
        $this->state = new ArticleStateMachine($this);
    }

    public static function createArticle(ArticleData $articleData): self
    {
        $article = self::retrieve($articleData->uuid);

        $article->recordThat(new ArticleWasCreated(
            articleUuid: $articleData->uuid,
            title: $articleData->title,
            slug: $articleData->slug,
            excerpt: $articleData->excerpt,
            content: $articleData->content,
            published: $articleData->published,
            featuredImage: $articleData->featuredImage,
            createdAt: now(),
        ));

        return $article;
    }

    public function updateArticle(ArticleData $articleData): self
    {
        $this->recordThat(new ArticleWasUpdated(
            articleUuid: $this->uuid(),
            title: $articleData->title,
            slug: $articleData->slug,
            excerpt: $articleData->excerpt,
            content: $articleData->content,
            published: $articleData->published,
            featuredImage: $articleData->featuredImage,
            updatedAt: now(),
        ));

        return $this;
    }

    public function shareArticle(): self
    {
        if ($this->state->isShared()) {
            throw ArticleException::articleCannotBeShared(
                articleUuid: $this->uuid(),
                reason: 'It has already been shared.',
            );
        }

        $this->recordThat(new ArticleWasShared(
            articleUuid: $this->uuid(),
            sharedAt: now(),
        ));

        return $this;
    }

    public function delete(): self
    {
        $this->recordThat(new ArticleWasDeleted(
            articleUuid: $this->uuid(),
        ));

        return $this;
    }
}

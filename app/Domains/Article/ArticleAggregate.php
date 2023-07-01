<?php

namespace App\Domains\Article;

use App\Domains\Article\DTOs\ArticleData;
use App\Domains\Article\Events\ArticleWasCreated;
use App\Domains\Article\Events\ArticleWasDeleted;
use App\Domains\Article\Events\ArticleWasPublished;
use App\Domains\Article\Events\ArticleWasTweeted;
use App\Domains\Article\Events\ArticleWasUpdated;
use App\Domains\Article\Exceptions\ArticleException;
use App\Domains\Article\Partials\ArticleStateMachine;
use App\Services\Twitter\DTOs\Tweet;
use Carbon\CarbonImmutable;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class ArticleAggregate extends AggregateRoot
{
    public ArticleStateMachine $state;

    public function __construct()
    {
        $this->state = new ArticleStateMachine($this);
    }

    public static function create(ArticleData $articleData): self
    {
        $article = self::retrieve($articleData->uuid);

        $article->recordThat(new ArticleWasCreated(
            uuid: $articleData->uuid,
            title: $articleData->title,
            slug: $articleData->slug,
            excerpt: $articleData->excerpt,
            content: $articleData->content,
            createdAt: CarbonImmutable::now(),
            tags: $articleData->tags,
            platforms: $articleData->platforms,
            postToTwitter: $articleData->postToTwitter,
        ));

        return $article;
    }

    public function update(ArticleData $articleData): self
    {
        $this->recordThat(new ArticleWasUpdated(
            uuid: $this->uuid(),
            title: $articleData->title,
            slug: $articleData->slug,
            excerpt: $articleData->excerpt,
            content: $articleData->content,
            updatedAt: CarbonImmutable::now(),
            tags: $articleData->tags,
            platforms: $articleData->platforms,
            postToTwitter: $articleData->postToTwitter,
        ));

        return $this;
    }

    public function publish(): self
    {
        if ($this->state->isPublished()) {
            throw ArticleException::articleCannotBePublished(
                uuid: $this->uuid(),
                reason: 'It has already been published.',
            );
        }

        $this->recordThat(new ArticleWasPublished(
            uuid: $this->uuid(),
            publishedAt: CarbonImmutable::now(),
        ));

        return $this;
    }

    public function articleTeeeted(Tweet $tweet): self
    {
        if ($this->state->isDraft()) {
            throw ArticleException::tweetCannotBeSent(
                uuid: $this->uuid(),
                reason: 'The article has not been published yet.',
            );
        }

        if ($this->state->tweetSent()) {
            throw ArticleException::tweetCannotBeSent(
                uuid: $this->uuid(),
                reason: 'A tweet has already been sent.',
            );
        }

        $this->recordThat(new ArticleWasTweeted(
            uuid: $this->uuid(),
            tweetId: $tweet->id,
            tweetContent: $tweet->content,
        ));
    }

    public function delete(): self
    {
        $this->recordThat(new ArticleWasDeleted(
            uuid: $this->uuid(),
        ));

        return $this;
    }
}

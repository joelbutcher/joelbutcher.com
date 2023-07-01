<?php

namespace App\Domains\Article\Partials;

use App\Domains\Article\ArticleAggregate;
use App\Domains\Article\Enums\ArticleStatus;
use App\Domains\Article\Events\ArticleWasPublished;
use App\Domains\Article\Events\ArticleWasTweeted;
use Spatie\EventSourcing\AggregateRoots\AggregatePartial;

final class ArticleStateMachine extends AggregatePartial
{
    public function __construct(
        ArticleAggregate $aggregate,
        private ?string $tweet = null,
        private ArticleStatus $status = ArticleStatus::Draft,
    ) {
        parent::__construct($aggregate);
    }

    public function onArticleWasPublished(ArticleWasPublished $event): void
    {
        $this->status = ArticleStatus::Published;
    }

    public function onArticleTweeted(ArticleWasTweeted $event): void
    {
        $this->tweet = $event->tweetId;
    }

    public function isDraft(): bool
    {
        return $this->is(ArticleStatus::Draft);
    }

    public function isPublished(): bool
    {
        return $this->is(ArticleStatus::Published);
    }

    public function tweetSent(): bool
    {
        return ! is_null($this->tweet);
    }

    public function is(ArticleStatus ...$statuses): bool
    {
        return in_array($this->status, $statuses);
    }

    /**
     * @return ArticleStatus
     */
    public function status(): ArticleStatus
    {
        return $this->status;
    }
}

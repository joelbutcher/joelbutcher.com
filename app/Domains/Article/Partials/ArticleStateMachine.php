<?php

namespace App\Domains\Article\Partials;

use App\Domains\Article\ArticleAggregate;
use App\Domains\Article\Enums\ArticleStatus;
use App\Domains\Article\Events\ArticleWasShared;
use Spatie\EventSourcing\AggregateRoots\AggregatePartial;

class ArticleStateMachine extends AggregatePartial
{
    private ArticleStatus $status;

    public function __construct(ArticleAggregate $aggregate)
    {
        parent::__construct($aggregate);
        $this->status = ArticleStatus::Unshared;
    }

    public function onArticleShared(ArticleWasShared $event): void
    {
        $this->status = ArticleStatus::Shared;
    }

    public function isShared(): bool
    {
        return $this->is(ArticleStatus::Shared);
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

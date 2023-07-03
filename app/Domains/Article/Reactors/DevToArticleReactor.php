<?php

namespace App\Domains\Article\Reactors;

use App\Domains\Article\Enums\Platform;
use App\Domains\Article\Events\ArticleWasPublished;
use App\Domains\Article\Exceptions\ArticleException;
use App\Domains\Article\Projections\Article;
use App\Services\DevTo\DevToService;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class DevToArticleReactor extends Reactor
{
    private const MARKDOWN_TAKEN = 'Body markdown has already been taken';

    public function __construct(
        private readonly DevToService $service,
    ) {
    }

    public function __invoke(ArticleWasPublished $event)
    {
        if (! $this->service->enabled()) {
            return;
        }

        if (! in_array(Platform::DevTo, $event->platforms)) {
            return;
        }

        $article = Article::findByUuid($event->uuid);

        if (! $article->platforms->contains(Platform::DevTo)) {
            return;
        }

        $devToResponse = $this->service->publish($article->toDto());

        $article->writeable()->update([
            'devto_response' => $devToResponse->json(),
        ]);

        if (! $devToResponse->successful()) {
            $json = json_encode($devToResponse->json());

            throw new ArticleException(
                message: "Failed to publish article to DevTo: $json",
                code: $devToResponse->status(),
            );
        }
    }
}

<?php

namespace App\Domains\Article\Reactors;

use App\Domains\Article\Enums\Platform;
use App\Domains\Article\Events\ArticleWasPublished;
use App\Domains\Article\Exceptions\ArticleException;
use App\Domains\Article\Projections\Article;
use App\Services\Hashnode\HashnodeService;
use Spatie\EventSourcing\EventHandlers\Reactors\Reactor;

class HashnodeArticleReactor extends Reactor
{
    public function __construct(
        private readonly HashnodeService $service,
    ) {
    }

    public function __invoke(ArticleWasPublished $event)
    {
        if (! $this->service->enabled()) {
            return;
        }

        if (! in_array(Platform::Hashnode, $event->platforms)) {
            return;
        }

        $article = Article::findByUuid($event->uuid);

        $hashnodeResponse = $this->service->publish($article->toDto());

        $article->writeable()->update([
            'hashnode_response' => $hashnodeResponse->json(),
        ]);

        if (! $hashnodeResponse->successful()) {
            $json = json_encode($hashnodeResponse->json());

            throw new ArticleException(
                message: "Failed to publish article to Hashnode: $json",
                code: $hashnodeResponse->status(),
            );
        }
    }
}

<?php

namespace App\Services\Medium;

use App\Domains\Article\DTOs\ArticleData;
use App\Http\Integrations\Medium\MediumConnector;
use App\Http\Integrations\Medium\Requests\PublishArticleRequest;

final readonly class MediumManager
{
    public function __construct(
        private MediumConnector $connector,
    ) {
    }

    public function publish(ArticleData $articleData): void
    {
        $this->connector->send(
            request: new PublishArticleRequest(
                articleData: $articleData
            ),
        );
    }
}

<?php

namespace App\Services\Hashnode;

use App\Domains\Article\DTOs\ArticleData;
use App\Http\Integrations\Hashnode\HashnodeConnector;
use App\Http\Integrations\Hashnode\Requests\PublishArticleRequest;
use App\Http\Integrations\Hashnode\Responses\PublishArticleResponse;

final readonly class HashnodeService
{
    public function __construct(
        private HashnodeConnector $connector,
    ) {
    }

    public function publish(ArticleData $article): PublishArticleResponse
    {
        /** @var PublishArticleResponse */
        return $this->connector->send(
            new PublishArticleRequest(
                article: $article
            ),
        );
    }
}

<?php

namespace App\Services\DevTo;

use App\Domains\Article\DTOs\ArticleData;
use App\Http\Integrations\DevTo\DevToConnector;
use App\Http\Integrations\DevTo\Requests\PublishArticleRequest;
use App\Http\Integrations\DevTo\Responses\PublishArticleResponse;

final readonly class DevToService
{
    public function __construct(
        private DevToConnector $connector,
    ) {
    }

    public function enabled(): bool
    {
        return config('services.devto.enabled');
    }

    public function publish(ArticleData $article): PublishArticleResponse
    {
        /** @var PublishArticleResponse */
        return $this->connector->send(
            request: new PublishArticleRequest(
                article: $article
            ),
        );
    }
}

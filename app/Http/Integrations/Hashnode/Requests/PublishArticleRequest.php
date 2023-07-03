<?php

namespace App\Http\Integrations\Hashnode\Requests;

use App\Domains\Article\DTOs\ArticleData;
use App\Http\Integrations\Hashnode\Responses\PublishArticleResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class PublishArticleRequest extends Request
{
    protected Method $method = Method::POST;

    protected ?string $response = PublishArticleResponse::class;

    public function __construct(
        private readonly ArticleData $article
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/example';
    }
}

<?php

namespace App\Http\Integrations\Hashnode\Requests;

use App\Domains\Article\DTOs\ArticleData;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class PostArticleRequest extends Request
{
    public function __construct(
        private readonly ArticleData $articleData
    ) {
    }

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        return '/example';
    }
}

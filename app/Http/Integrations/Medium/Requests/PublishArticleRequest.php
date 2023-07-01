<?php

namespace App\Http\Integrations\Medium\Requests;

use App\Domains\Article\DTOs\ArticleData;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class PublishArticleRequest extends Request implements HasBody
{
    use HasJsonBody;

    public function __construct(
        private readonly ArticleData $articleData,
    ) {
    }

    protected Method $method = Method::POST;

    public function resolveEndpoint(): string
    {
        $userId = config('services.medium.user_id');

        return "/users/$userId/posts";
    }

    protected function defaultBody(): array
    {
        return [
            'title' => $this->articleData->title,
            'content' => $this->articleData->content,
            'contentFormat' => 'markdown',
            'publishStatus' => 'public',
            'tags' => $this->articleData->tags,
        ];
    }
}

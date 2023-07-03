<?php

namespace App\Http\Integrations\DevTo\Requests;

use App\Domains\Article\DTOs\ArticleData;
use App\Http\Integrations\DevTo\Responses\PublishArticleResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class PublishArticleRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    protected ?string $response = PublishArticleResponse::class;

    public function __construct(
        private readonly ArticleData $article
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/articles';
    }

    protected function defaultBody(): array
    {
        return [
            'article' => [
                'title' => $this->article->title,
                'description' => $this->article->excerpt,
                'body_markdown' => $this->article->content,
                'published' => true,
                'tags' => $this->article->tags,
                'series' => $this->article->series,
            ],
        ];
    }
}

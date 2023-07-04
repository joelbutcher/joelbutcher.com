<?php

namespace App\Http\Integrations\Hashnode\Requests;

use App\Domains\Article\DTOs\ArticleData;
use App\Http\Integrations\Hashnode\Responses\PublishArticleResponse;
use App\Services\Hashnode\ValueObjects\PublicationId;
use Illuminate\Support\Facades\Storage;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class PublishArticleRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    protected ?string $response = PublishArticleResponse::class;

    private readonly PublicationId $publicationId;

    public function __construct(
        private readonly ArticleData $article
    ) {
        $this->publicationId = PublicationId::new();
    }

    public function resolveEndpoint(): string
    {
        return '/graphql';
    }

    protected function defaultBody(): array
    {
        $coverImageUrl = $this->coverImageUrl();

        return [
            'query' => 'mutation createPublicationStory($input: CreateStoryInput!, $publicationId: String!){ createPublicationStory(publicationId: $publicationId, input: $input){ code success message } }',
            'variables' => [
                'publicationId' => $this->publicationId->toString(),
                'input' => array_merge([
                    'title' => $this->article->title,
                    'contentMarkdown' => $this->article->content,
                    'tags' => collect($this->article->tags)->map(fn ($tag) => [
                        'slug' => $id = strtolower($tag),
                        'name' => $tag,
                        '_id' => md5($id),
                    ])->toArray(),
                    'isPartOfPublication' => [
                        'publicationId' => $this->publicationId->toString(),
                    ],
                ], $coverImageUrl ? [
                    'coverImageURL' => $coverImageUrl,
                ] : []),
            ],
        ];
    }

    private function coverImageUrl(): ?string
    {
        return $this->article->imagePath
            ? Storage::disk('s3')->url($this->article->imagePath)
            : null;
    }
}

<?php

namespace App\Services\Twitter;

use App\Domains\Article\Projections\Article;

class TwitterStatusUpdate implements TwitterAction
{
    public function __construct(
        private readonly string $status
    ) {
    }

    public static function forNewArticle(Article $article): self
    {
        $title = $article->title;
        $slug = $article->slug;

        $domain = rtrim(config('app.url'), '/');
        $url = "$domain/blog/$slug";

        return new self("New article \"{$title}\" posted, check it out on my blog\n\n$url");
    }

    public function apiPath(): string
    {
        return 'statuses/update';
    }

    public function requestBody(): array
    {
        return [
            'status' => $this->status,
        ];
    }
}

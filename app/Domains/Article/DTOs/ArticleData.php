<?php

namespace App\Domains\Article\DTOs;

use App\Domains\Article\Enums\Platform;
use App\Domains\Article\Projections\Article;
use Statamic\Entries\Entry;

readonly class ArticleData
{
    /**
     * @param  array<string>  $tags
     * @param  array<Platform>  $platforms
     */
    public function __construct(
        public string $uuid,
        public string $title,
        public string $slug,
        public ?string $series,
        public string $excerpt,
        public ?string $imagePath,
        public ?string $content,
        public bool $published = false,
        public array $tags = [],
        public array $platforms = [],
        public bool $postToTwitter = false,
    ) {
    }

    public static function fromEntry(Entry $entry): self
    {
        return new self(
            uuid: $entry->id(),
            title: $entry->get('title'),
            slug: $entry->slug(),
            series: $entry->get('series'),
            excerpt: $entry->get('excerpt'),
            imagePath: $entry->get('image'),
            content: $entry->get('content'),
            published: $entry->published(),
            tags: $entry->get('tags', []),
            platforms: array_map(
                callback: fn (string $destination): Platform => Platform::from($destination),
                array: $entry->get('platforms', []),
            ),
            postToTwitter: $entry->get('post_to_twitter', false),
        );
    }

    public static function fromModel(Article $article): self
    {
        return new self(
            uuid: $article->uuid,
            title: $article->title,
            slug: $article->slug,
            series: null,
            excerpt: $article->excerpt,
            imagePath: $article->image_path,
            content: $article->content,
            published: $article->hasBeenPublished,
            tags: $article->tags,
            platforms: $article->platforms->toArray(),
            postToTwitter: $article->hasBeenTweeted,
        );
    }
}

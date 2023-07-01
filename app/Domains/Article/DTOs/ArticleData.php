<?php

namespace App\Domains\Article\DTOs;

use App\Domains\Article\Enums\Platform;
use Statamic\Entries\Entry;

readonly class ArticleData
{
    public function __construct(
        public string $uuid,
        public string $title,
        public string $slug,
        public string $excerpt,
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
            excerpt: $entry->get('excerpt'),
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
}

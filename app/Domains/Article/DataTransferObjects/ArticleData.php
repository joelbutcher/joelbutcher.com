<?php

namespace App\Domains\Article\DataTransferObjects;

use Statamic\Entries\Entry;

class ArticleData
{
    public function __construct(
        public readonly string $uuid,
        public readonly string $title,
        public readonly string $slug,
        public readonly string $excerpt,
        public readonly array $content,
        public readonly bool $published,
        public readonly ?string $featuredImage,
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
            featuredImage: $entry->get('featured_image'),
        );
    }
}

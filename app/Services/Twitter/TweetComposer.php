<?php

namespace App\Services\Twitter;

final class TweetComposer
{
    public static function compose(string $title, string $slug, array $tags): string
    {
        $url = self::buildUrl($slug);

        $tags = collect($tags)->map(fn (string $tag) => "#$tag")->implode(' ');

        return "📝 New blog post: $title\n\n$url\n\n$tags";
    }

    private static function buildUrl(string $slug): string
    {
        $appUrl = config('app.url');

        return "$appUrl/articles/$slug";
    }
}

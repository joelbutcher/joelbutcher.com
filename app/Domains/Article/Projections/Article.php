<?php

namespace App\Domains\Article\Projections;

use App\Services\Twitter\Casts\TweetCast;
use App\Services\Twitter\DTOs\Tweet;
use Carbon\CarbonImmutable;
use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EventSourcing\Projections\Projection;

/**
 * @property string $uuid
 * @property string $title
 * @property string $slug
 * @property string $excerpt
 * @property ?string $content
 * @property array $tags
 * @property array $platforms
 * @property ?Tweet $tweet
 * @property CarbonImmutable|null $tweeted_at
 * @property CarbonImmutable|null $published_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
class Article extends Projection
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'published_at' => 'immutable_datetime',
        'tags' => 'array',
        'platforms' => 'array',
        'tweet' => TweetCast::class,
    ];

    public function hasBeenPublished(): bool
    {
        return ! is_null($this->published_at);
    }

    public function hasBeenTweeted(): bool
    {
        return ! is_null($this->tweeted_at);
    }

    public static function findByUuid(string $uuid): ?Article
    {
        /** @var Article $article */
        $article = self::query()->where('uuid', $uuid)->first();

        return $article;
    }

    protected static function newFactory(): ArticleFactory
    {
        return ArticleFactory::new();
    }

    public function composeTweet(): string
    {
        $intro = "📝 New blog post: $this->title";

        $tags = collect($this->tags)->map(fn (string $tag) => "#$tag")->implode(' ');

        return "$intro\n\n{$this->url()}\n\n$tags";
    }

    public function url(): string
    {
        return sprintf(
            '%s/articles/%s',
            config('app.url'),
            $this->slug,
        );
    }
}

<?php

namespace App\Domains\Article\Projections;

use App\Domains\Article\Casts\PlatformsCast;
use App\Domains\Article\DTOs\ArticleData;
use App\Domains\Article\Enums\Platform;
use App\Services\Twitter\Casts\TweetCast;
use App\Services\Twitter\DTOs\Tweet;
use App\Services\Twitter\TweetComposer;
use Carbon\CarbonImmutable;
use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Spatie\EventSourcing\Projections\Projection;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property string $uuid
 * @property string $title
 * @property string $slug
 * @property ?string $series
 * @property string $excerpt
 * @property ?string $image_path
 * @property ?string $content
 * @property ?array $tags
 * @property Collection<Platform> $platforms
 * @property ?Tweet $tweet
 * @property ?array $devto_response
 * @property ?array $hashnode_response
 * @property bool hasBeenPublished
 * @property bool hasBeenTweeted
 * @property CarbonImmutable|null $tweeted_at
 * @property CarbonImmutable|null $published_at
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 *
 * @method static ArticleFactory factory($count = null, $state = [])
 */
class Article extends Projection
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;

    protected $guarded = [];

    protected $casts = [
        'tags' => 'array',
        'platforms' => PlatformsCast::class,
        'tweet' => TweetCast::class,
        'devto_response' => 'array',
        'hashnode_response' => 'array',
        'published_at' => 'immutable_datetime',
        'tweeted_at' => 'immutable_datetime',
    ];

    public function hasBeenPublished(): Attribute
    {
        return Attribute::get(fn () => ! is_null($this->published_at));
    }

    public function hasBeenTweeted(): Attribute
    {
        return Attribute::get(fn () => ! is_null($this->tweeted_at));
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
        return TweetComposer::compose($this->title, $this->slug, $this->tags ?? []);
    }

    public function toDto(): ArticleData
    {
        return ArticleData::fromModel($this);
    }
}

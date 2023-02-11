<?php

namespace App\Domains\Article\Projections;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EventSourcing\Projections\Projection;

/**
 * @property string $uuid
 * @property string $title
 * @property string $slug
 * @property string $excerpt
 * @property ?string $content
 * @property bool $published
 * @property string $tweet_id
 * @property string|null $featured_image
 * @property Carbon|null $shared_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Article extends Projection
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'published' => 'boolean',
    ];

    public function hasBeenShared(): bool
    {
        return null !== $this->shared_at;
    }

    public static function uuid(string $uuid): ?Article
    {
        /** @var Article $article */
        $article = self::query()->where('uuid', $uuid)->first();

        return $article;
    }
}

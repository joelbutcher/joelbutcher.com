<?php

use App\Domains\Article\Actions\SaveArticle;
use App\Domains\Article\DTOs\ArticleData;
use App\Domains\Article\Events\ArticleWasCreated;
use App\Domains\Article\Events\ArticleWasPublished;
use App\Domains\Article\Events\ArticleWasTweeted;
use App\Domains\Article\Events\ArticleWasUpdated;
use App\Domains\Article\Projections\Article;
use App\Http\Integrations\Twitter\Requests\SendTweetRequest;
use App\Services\Twitter\DTOs\Tweet;
use App\Services\Twitter\TweetComposer;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Config;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;
use Tests\TestCase;

uses(TestCase::class);

it('Creates a new article, publishes it and tweets it', function () {
    Config::set('services.twitter.enabled', true);

    $article = new ArticleData(
        uuid: fake()->uuid,
        title: $title = fake()->sentence,
        slug: $slug = fake()->slug,
        series: null,
        excerpt: fake()->paragraph(),
        imagePath: null,
        content: fake()->paragraphs(asText: true),
        published: true,
        tags: $tags = ['foo', 'bar'],
        platforms: [],
        postToTwitter: true,
    );

    Saloon::fake([
        SendTweetRequest::class => MockResponse::make([
            'data' => [
                'id' => $tweetId = fake()->uuid,
                'text' => $tweetContent = TweetComposer::compose(
                    title: $title,
                    slug: $slug,
                    tags: $tags,
                ),
            ],
        ]),
    ]);

    app(SaveArticle::class)(
        data: $article,
    );

    expect(Article::all())->toHaveCount(1);

    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasCreated::class]],
    ]);

    $this->assertDatabaseMissing(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasUpdated::class]],
    ]);

    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasPublished::class]],
    ]);

    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasTweeted::class]],
    ]);

    Saloon::assertSent(SendTweetRequest::class);

    expect(Article::first())
        ->title->toEqual($title)
        ->slug->toEqual($slug)
        ->excerpt->toEqual($article->excerpt)
        ->content->toEqual($article->content)
        ->published_at->toBeInstanceOf(CarbonImmutable::class)
        ->hasBeenPublished->toBeTrue()
        ->tags->toEqual($tags)
        ->platforms->toEqual(collect($article->platforms))
        ->tweeted_at->toBeInstanceOf(CarbonImmutable::class)
        ->hasBeenTweeted->toBeTrue()
        ->tweet->toEqual(new Tweet(
            id: $tweetId,
            content: $tweetContent,
        ));
});

it('Creates a new article, publishes it and does not tweet it', function () {
    Config::set('services.twitter.enabled', true);

    $article = new ArticleData(
        uuid: fake()->uuid,
        title: $title = fake()->sentence,
        slug: $slug = fake()->slug,
        series: null,
        excerpt: fake()->paragraph(),
        imagePath: null,
        content: fake()->paragraphs(asText: true),
        published: true,
        tags: $tags = ['foo', 'bar'],
        platforms: [],
        postToTwitter: false,
    );

    app(SaveArticle::class)(
        data: $article,
    );

    expect(Article::all())->toHaveCount(1);

    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasCreated::class]],
    ]);

    $this->assertDatabaseMissing(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasUpdated::class]],
    ]);

    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasPublished::class]],
    ]);

    $this->assertDatabaseMissing(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasTweeted::class]],
    ]);

    Saloon::assertNotSent(SendTweetRequest::class);

    expect(Article::first())
        ->title->toEqual($title)
        ->slug->toEqual($slug)
        ->excerpt->toEqual($article->excerpt)
        ->content->toEqual($article->content)
        ->published_at->toBeInstanceOf(CarbonImmutable::class)
        ->hasBeenPublished->toBeTrue()
        ->tags->toEqual($tags)
        ->platforms->toEqual(collect($article->platforms))
        ->tweeted_at->toBeNull()
        ->hasBeenTweeted->toBeFalse()
        ->tweet->toBeNull();
});

it('Creates a new article and does not publishes or tweet it', function () {
    Config::set('services.twitter.enabled', true);

    $article = new ArticleData(
        uuid: fake()->uuid,
        title: $title = fake()->sentence,
        slug: $slug = fake()->slug,
        series: null,
        excerpt: fake()->paragraph(),
        imagePath: null,
        content: fake()->paragraphs(asText: true),
        published: false,
        tags: $tags = ['foo', 'bar'],
        platforms: [],
        postToTwitter: false,
    );

    app(SaveArticle::class)(
        data: $article,
    );

    expect(Article::all())->toHaveCount(1);

    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasCreated::class]],
    ]);

    $this->assertDatabaseMissing(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasUpdated::class]],
    ]);

    $this->assertDatabaseMissing(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasPublished::class]],
    ]);

    $this->assertDatabaseMissing(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasTweeted::class]],
    ]);

    Saloon::assertNotSent(SendTweetRequest::class);

    expect(Article::first())
        ->title->toEqual($title)
        ->slug->toEqual($slug)
        ->excerpt->toEqual($article->excerpt)
        ->content->toEqual($article->content)
        ->published_at->toBeNull()
        ->hasBeenPublished->toBeFalse()
        ->tags->toEqual($tags)
        ->platforms->toEqual(collect($article->platforms))
        ->tweeted_at->toBeNull()
        ->hasBeenTweeted->toBeFalse()
        ->tweet->toBeNull();
});

it('Update an existing article, publishes it and tweets it', function () {
    Config::set('services.twitter.enabled', true);

    /** @var Article $article */
    $article = Article::factory()->create();

    $this->assertDatabaseMissing(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasUpdated::class]],
    ]);

    $article = new ArticleData(
        uuid: $article->uuid,
        title: $title = fake()->sentence,
        slug: $slug = fake()->slug,
        series: null,
        excerpt: fake()->paragraph(),
        imagePath: null,
        content: fake()->paragraphs(asText: true),
        published: true,
        tags: $tags = ['foo', 'bar'],
        platforms: [],
        postToTwitter: true,
    );

    Saloon::fake([
        SendTweetRequest::class => MockResponse::make([
            'data' => [
                'id' => $tweetId = fake()->uuid,
                'text' => $tweetContent = TweetComposer::compose(
                    title: $title,
                    slug: $slug,
                    tags: $tags,
                ),
            ],
        ]),
    ]);

    app(SaveArticle::class)(
        data: $article,
    );

    expect(Article::all())->toHaveCount(1);

    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasUpdated::class]],
    ]);

    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasPublished::class]],
    ]);

    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasTweeted::class]],
    ]);

    Saloon::assertSent(SendTweetRequest::class);

    expect(Article::first())
        ->title->toEqual($title)
        ->slug->toEqual($slug)
        ->excerpt->toEqual($article->excerpt)
        ->content->toEqual($article->content)
        ->published_at->toBeInstanceOf(CarbonImmutable::class)
        ->hasBeenPublished->toBeTrue()
        ->tags->toEqual($tags)
        ->platforms->toEqual(collect($article->platforms))
        ->tweeted_at->toBeInstanceOf(CarbonImmutable::class)
        ->hasBeenTweeted->toBeTrue()
        ->tweet->toEqual(new Tweet(
            id: $tweetId,
            content: $tweetContent,
        ));
});

it('Update an existing article, publishes it and does not tweet it', function () {
    Config::set('services.twitter.enabled', true);

    /** @var Article $article */
    $article = Article::factory()->create();

    $this->assertDatabaseMissing(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasUpdated::class]],
    ]);

    $article = new ArticleData(
        uuid: $article->uuid,
        title: $title = fake()->sentence,
        slug: $slug = fake()->slug,
        series: null,
        excerpt: fake()->paragraph(),
        imagePath: null,
        content: fake()->paragraphs(asText: true),
        published: true,
        tags: $tags = ['foo', 'bar'],
        platforms: [],
        postToTwitter: false,
    );

    app(SaveArticle::class)(
        data: $article,
    );

    expect(Article::all())->toHaveCount(1);

    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasUpdated::class]],
    ]);

    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasPublished::class]],
    ]);

    $this->assertDatabaseMissing(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasTweeted::class]],
    ]);

    Saloon::assertNotSent(SendTweetRequest::class);

    expect(Article::first())
        ->title->toEqual($title)
        ->slug->toEqual($slug)
        ->excerpt->toEqual($article->excerpt)
        ->content->toEqual($article->content)
        ->published_at->toBeInstanceOf(CarbonImmutable::class)
        ->hasBeenPublished->toBeTrue()
        ->tags->toEqual($tags)
        ->platforms->toEqual(collect($article->platforms))
        ->tweeted_at->toBeNull()
        ->hasBeenTweeted->toBeFalse()
        ->tweet->toBeNull();
});

it('Update an existing article and does not publishes or tweet it', function () {
    Config::set('services.twitter.enabled', true);

    /** @var Article $article */
    $article = Article::factory()->create();

    $this->assertDatabaseMissing(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasUpdated::class]],
    ]);

    $article = new ArticleData(
        uuid: $article->uuid,
        title: $title = fake()->sentence,
        slug: $slug = fake()->slug,
        series: null,
        excerpt: fake()->paragraph(),
        imagePath: null,
        content: fake()->paragraphs(asText: true),
        published: false,
        tags: $tags = ['foo', 'bar'],
        platforms: [],
        postToTwitter: false,
    );

    app(SaveArticle::class)(
        data: $article,
    );

    expect(Article::all())->toHaveCount(1);

    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasUpdated::class]],
    ]);

    $this->assertDatabaseMissing(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasPublished::class]],
    ]);

    $this->assertDatabaseMissing(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasTweeted::class]],
    ]);

    Saloon::assertNotSent(SendTweetRequest::class);

    expect(Article::first())
        ->title->toEqual($title)
        ->slug->toEqual($slug)
        ->excerpt->toEqual($article->excerpt)
        ->content->toEqual($article->content)
        ->published_at->toBeNull()
        ->hasBeenPublished->toBeFalse()
        ->tags->toEqual($tags)
        ->platforms->toEqual(collect($article->platforms))
        ->tweeted_at->toBeNull()
        ->hasBeenTweeted->toBeFalse()
        ->tweet->toBeNull();
});

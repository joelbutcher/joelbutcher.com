<?php

use App\Domains\Article\Actions\PublishArticle;
use App\Domains\Article\Actions\TweetArticle;
use App\Domains\Article\Events\ArticleWasTweeted;
use App\Domains\Article\Exceptions\ArticleException;
use App\Domains\Article\Projections\Article;
use App\Http\Integrations\Twitter\Requests\SendTweetRequest;
use App\Services\Twitter\DTOs\Tweet;
use App\Services\Twitter\TweetComposer;
use Illuminate\Support\Facades\Config;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;
use Tests\TestCase;

uses(TestCase::class);

it('does not send a tweet if twitter is disabled', function () {
   Config::set('services.twitter.enabled', false);

    /** @var Article $article */
    $article = Article::factory()->create();

    Saloon::fake([
        SendTweetRequest::class => MockResponse::make([
            'data' => [
                'id' => 123,
                'text' => TweetComposer::compose(
                    title: $article->title,
                    slug: $article->slug,
                    tags: $article->tags ?? []
                ),
            ],
        ]),
    ]);

    app(PublishArticle::class)(
        article: $article,
    );

    app(TweetArticle::class)(
        article: $article
    );

    Saloon::assertNotSent(SendTweetRequest::class);

    expect($article->refresh())
        ->tweet->toBeNull()
        ->hasBeenTweeted->toBeFalse();
});

it('tweets about an article once it is published', function () {
    Config::set('services.twitter.enabled', true);

    /** @var Article $article */
    $article = Article::factory()->create();

    Saloon::fake([
        SendTweetRequest::class => MockResponse::make([
            'data' => [
                'id' => 123,
                'text' => TweetComposer::compose(
                    title: $article->title,
                    slug: $article->slug,
                    tags: $article->tags ?? []
                ),
            ],
        ]),
    ]);

    app(PublishArticle::class)(
        article: $article,
    );

    app(TweetArticle::class)(
        article: $article
    );

    Saloon::assertSent(SendTweetRequest::class);

    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasTweeted::class]],
    ]);

    expect($article->refresh())
        ->tweet->toEqual(new Tweet(
            id: 123,
            content: TweetComposer::compose(
                title: $article->title,
                slug: $article->slug,
                tags: $article->tags ?? []
            ),
        ))
        ->hasBeenTweeted->toBeTrue();
});

it('cannot tweet about an unpublished article', function () {
    Config::set('services.twitter.enabled', true);

    /** @var Article $article */
    $article = Article::factory()->create();

    expect(fn() => app(TweetArticle::class)(
        article: $article
    ))->toThrow(
        exception: ArticleException::class,
        exceptionMessage: "Could not sent tweet for article [$article->uuid] The article has not been published yet."
    )
        ->and($article->refresh())
        ->tweet->toBeNull()
        ->hasBeenTweeted->toBeFalse();

});

it('cannot tweet about an article that has already been tweeted', function () {
    Config::set('services.twitter.enabled', true);

    /** @var Article $article */
    $article = Article::factory()->create();

    Saloon::fake([
        SendTweetRequest::class => MockResponse::make([
            'data' => [
                'id' => 123,
                'text' => TweetComposer::compose(
                    title: $article->title,
                    slug: $article->slug,
                    tags: $article->tags ?? []
                ),
            ],
        ]),
    ]);

    app(PublishArticle::class)(
        article: $article,
    );

    app(TweetArticle::class)(
        article: $article
    );

    expect($article->refresh())
        ->tweet->toEqual(new Tweet(
            id: 123,
            content: TweetComposer::compose(
                title: $article->title,
                slug: $article->slug,
                tags: $article->tags ?? []
            ),
        ))
        ->hasBeenTweeted->toBeTrue();

    $callback = fn () => app(TweetArticle::class)(
        article: $article
    );

    expect($callback)->toThrow(
        exception: ArticleException::class,
        exceptionMessage: "Could not sent tweet for article [$article->uuid] A tweet has already been sent."
    );
});

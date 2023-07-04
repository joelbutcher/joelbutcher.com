<?php

use App\Domains\Article\Actions\PublishArticle;
use App\Domains\Article\Enums\Platform;
use App\Domains\Article\Events\ArticleWasPublished;
use App\Domains\Article\Projections\Article;
use App\Http\Integrations\DevTo\Requests\PublishArticleRequest as DevToPublishArticleRequest;
use App\Http\Integrations\Hashnode\Requests\PublishArticleRequest as HashnodePublishArticleRequest;
use Illuminate\Support\Facades\Config;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;
use Tests\TestCase;

uses(TestCase::class);

it('publishes to Hashnode', function () {
    Config::set('services.hashnode.enabled', true);

    /** @var Article $article */
    $article = Article::factory()->create();

    Saloon::fake([
        HashnodePublishArticleRequest::class => MockResponse::make([
            'data' => [
                'message' => 'Publication article created successfully',
                'code' => 200,
                'success' => true,
            ],
        ]),
    ]);

    app(PublishArticle::class)(
        $article,
        Platform::Hashnode,
    );

    Saloon::assertSent(HashnodePublishArticleRequest::class);

    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasPublished::class]],
    ]);

    expect($article->refresh())
        ->published_at->not->toBeNull()
        ->hasBeenPublished->toBeTrue()
        ->platforms->toHaveCount(1)->toContain(Platform::Hashnode)
        ->hashnode_response->not->toBeNull();
});

it('publishes to DevTo', function () {
    Config::set('services.devto.enabled', true);

    /** @var Article $article */
    $article = Article::factory()->create();

    Saloon::fake([
        DevToPublishArticleRequest::class => MockResponse::make([
            'data' => [
                'message' => 'Publication article created successfully',
                'code' => 200,
                'success' => true,
            ],
        ]),
    ]);

    app(PublishArticle::class)(
        $article,
        Platform::DevTo,
    );

    Saloon::assertSent(DevToPublishArticleRequest::class);

    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasPublished::class]],
    ]);

    expect($article->refresh())
        ->published_at->not->toBeNull()
        ->hasBeenPublished->toBeTrue()
        ->platforms->toHaveCount(1)->toContain(Platform::DevTo)
        ->devto_response->not->toBeNull();
});

it('does not publish to Hashnode if the service is disabled', function () {
    Config::set('services.hashnode.enabled', false);

    /** @var Article $article */
    $article = Article::factory()->create();

    Saloon::fake([
        HashnodePublishArticleRequest::class => MockResponse::make([
            'data' => [
                'message' => 'Publication article created successfully',
                'code' => 200,
                'success' => true,
            ],
        ]),
    ]);

    app(PublishArticle::class)(
        $article,
        Platform::Hashnode,
    );

    Saloon::assertNotSent(HashnodePublishArticleRequest::class);

    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasPublished::class]],
    ]);

    expect($article->refresh())
        ->published_at->not->toBeNull()
        ->hasBeenPublished->toBeTrue()
        ->platforms->toBeEmpty()
        ->hashnode_response->toBeNull();
});

it('does not publish to DevTo if the service is disabled', function () {
    Config::set('services.devto.enabled', false);

    /** @var Article $article */
    $article = Article::factory()->create();

    Saloon::fake([
        DevToPublishArticleRequest::class => MockResponse::make([
            'data' => [
                'message' => 'Publication article created successfully',
                'code' => 200,
                'success' => true,
            ],
        ]),
    ]);

    app(PublishArticle::class)(
        $article,
        Platform::DevTo,
    );

    Saloon::assertNotSent(DevToPublishArticleRequest::class);

    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasPublished::class]],
    ]);

    expect($article->refresh())
        ->published_at->not->toBeNull()
        ->hasBeenPublished->toBeTrue()
        ->platforms->toBeEmpty()
        ->hashnode_response->toBeNull();
});

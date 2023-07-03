<?php

use App\Domains\Article\Enums\Platform;
use App\Domains\Article\Events\ArticleWasPublished;
use App\Domains\Article\Projections\Article;
use App\Domains\Article\Reactors\DevToArticleReactor;
use App\Http\Integrations\DevTo\Requests\PublishArticleRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Tests\TestCase;

uses(TestCase::class);

it('publishes an article via the DevTo public API', function () {
    /** @var Article $article */
    $article = Article::factory()->published(
        Platform::DevTo,
    )->create();

    Saloon::fake([
        PublishArticleRequest::class => MockResponse::make(),
    ]);

    app(DevToArticleReactor::class)(
        event: new ArticleWasPublished(
            $article->uuid,
            $article->published_at,
            ...$article->platforms,
        ),
    );

    Saloon::assertSent(PublishArticleRequest::class);
});

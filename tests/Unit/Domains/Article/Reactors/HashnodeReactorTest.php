<?php

use App\Domains\Article\Enums\Platform;
use App\Domains\Article\Events\ArticleWasPublished;
use App\Domains\Article\Projections\Article;
use App\Domains\Article\Reactors\HashnodeArticleReactor;
use App\Http\Integrations\Hashnode\Requests\PublishArticleRequest;
use Saloon\Http\Faking\MockResponse;
use Saloon\Laravel\Facades\Saloon;
use Tests\TestCase;

uses(TestCase::class);

it('publishes an article via the Hashnode public API', function () {
    /** @var Article $article */
    $article = Article::factory()->published(
        Platform::Hashnode,
    )->create();

    Saloon::fake([
        PublishArticleRequest::class => MockResponse::make(),
    ]);

    app(HashnodeArticleReactor::class)(
        event: new ArticleWasPublished(
            $article->uuid,
            $article->published_at,
            ...$article->platforms,
        ),
    );

    Saloon::assertSent(PublishArticleRequest::class);
});

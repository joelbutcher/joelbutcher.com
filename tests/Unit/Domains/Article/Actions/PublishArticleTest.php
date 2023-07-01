<?php

namespace App\Domains\Article\Tests\Unit\Actions;

use App\Domains\Article\Actions\PublishArticle;
use App\Domains\Article\Events\ArticleWasPublished;
use App\Domains\Article\Projections\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class, WithFaker::class);

it('fires the "ArticleWasPublished" event and publishes an article', function () {
    /** @var Article $article */
    $article = Article::factory()->create();

    (new PublishArticle)(
        article: $article,
    );

    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasPublished::class]],
    ]);

    expect(Article::all())->toHaveCount(1)
        ->and($article->refresh()->hasBeenPublished())->toBeTrue();
});

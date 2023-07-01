<?php

namespace App\Domains\Article\Tests\Unit\Actions;

use App\Domains\Article\Actions\UpdateArticle;
use App\Domains\Article\DTOs\ArticleData;
use App\Domains\Article\Events\ArticleWasUpdated;
use App\Domains\Article\Projections\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class, WithFaker::class);

it('fires the "ArticleWasUpdate" event and updates an existing article', function () {
    /** @var Article $article */
    $article = Article::factory()->create();

    $this->assertDatabaseMissing(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasUpdated::class]],
    ]);

    (new UpdateArticle())(
        articleData: new ArticleData(
            uuid: $article->uuid,
            title: $article->title,
            slug: $article->slug,
            excerpt: $article->excerpt,
            content: fake()->paragraphs(2, asText: true),
        ),
    );

    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasUpdated::class]],
    ]);

    expect(Article::all())->toHaveCount(1);
});

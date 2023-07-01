<?php

namespace App\Domains\Article\Tests\Unit\Actions;

use App\Domains\Article\Actions\CreateArticle;
use App\Domains\Article\DTOs\ArticleData;
use App\Domains\Article\Events\ArticleWasCreated;
use App\Domains\Article\Projections\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class, WithFaker::class);

it('fires the "ArticleWasCreated" event and creates an article', function () {
    (new CreateArticle)(
        articleData: new ArticleData(
            uuid: $this->faker->uuid,
            title: $this->faker->sentence,
            slug: $this->faker->slug,
            excerpt: $this->faker->paragraph(),
            content: $this->faker->paragraphs(asText: true),
            published: true,
        ),
    );

    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [['event_class' => ArticleWasCreated::class]],
    ]);

    expect(Article::all())->toHaveCount(1);
});

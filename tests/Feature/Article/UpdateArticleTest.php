<?php

use App\Domains\Article\Actions\UpdateArticle;
use App\Domains\Article\DataTransferObjects\ArticleData;
use App\Domains\Article\Events\ArticleWasShared;
use App\Domains\Article\Events\ArticleWasUpdated;
use App\Domains\Article\Projections\Article;
use App\Domains\Article\Projectors\ArticleProjector;
use App\Services\Twitter\Twitter;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

beforeEach(function () {
    createArticle();

    /** @var Article $article */
    $article = Article::first();

    (new UpdateArticle())(
        article: $article,
        articleData: new ArticleData(
            uuid: $article->uuid,
            title: 'Why Laravel is Awesome',
            slug: 'why-laravel-is-awesome',
            excerpt: $article->excerpt,
            content: $article->content,
            published: false,
            featuredImage: null,
        ),
    );
});

it('stores the event', function () {
    $this->assertDatabaseCount(new EloquentStoredEvent(), 2);
    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [
            ['event_class' => ArticleWasUpdated::class],
        ],
    ]);
});

it('updates the article', function () {
    $this->assertDatabaseHas(new Article(), [
        'uuid' => '9f16f32e-0ebc-4265-9bf9-4c477edbcda2',
        'title' => 'Why Laravel is Awesome',
        'slug' => 'why-laravel-is-awesome',
        'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget odio pretium dolor sodales tempor in id sapien.',
        'content' => json_encode(['Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget odio pretium dolor sodales tempor in id sapien.']),
        'published' => false,
    ]);
});

it('shares the article when it is published', function () {
    $article = Article::first();

    $data = [
        'uuid' => '9f16f32e-0ebc-4265-9bf9-4c477edbcda2',
        'title' => 'Why Laravel is Awesome',
        'slug' => 'why-laravel-is-awesome',
        'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget odio pretium dolor sodales tempor in id sapien.',
        'content' => ['Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget odio pretium dolor sodales tempor in id sapien.'],
        'published' => true,
        'featuredImage' => '',
    ];

    $twitter = $this->mock(Twitter::class);
    $twitter->shouldReceive('post')->once()->andReturn([
        'id' => $tweetId = fake()->uuid(),
    ]);

    $this->app->when([ArticleProjector::class])
        ->needs(Twitter::class)
        ->give(fn () => $twitter);

    // Act
    (new UpdateArticle())(
        article: $article,
        articleData: new ArticleData(...$data),
    );

    // Assert
    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [
            ['event_class' => ArticleWasShared::class],
        ],
    ]);

    $this->assertDatabaseHas(new Article(), [
        'published' => true,
        'tweet_id' => $tweetId,
    ]);
});

<?php

use App\Domains\Article\Actions\DeleteArticle;
use App\Domains\Article\Actions\UpdateArticle;
use App\Domains\Article\DataTransferObjects\ArticleData;
use App\Domains\Article\Events\ArticleWasDeleted;
use App\Domains\Article\Projections\Article;
use App\Domains\Article\Projectors\ArticleProjector;
use App\Services\Twitter\Twitter;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

beforeEach(function () {
    $article = createArticle();

    (new UpdateArticle())(article: $article, articleData: new ArticleData(
        ...[
            'uuid' => '9f16f32e-0ebc-4265-9bf9-4c477edbcda2',
            'title' => 'Why Laravel is Awesome',
            'slug' => 'why-laravel-is-awesome',
            'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget odio pretium dolor sodales tempor in id sapien.',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget odio pretium dolor sodales tempor in id sapien.',
            'published' => false,
            'featuredImage' => '',
        ],
    ));
});

it('stores the event', function () {
    /** @var Article $article */
    $article = Article::first();

    (new DeleteArticle())(article: $article);

    // created, updated, deleted
    $this->assertDatabaseCount(new EloquentStoredEvent(), 3);
    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [
            ['event_class' => ArticleWasDeleted::class],
        ],
    ]);
});

it('deletes deletes the article', function () {
    /** @var Article $article */
    $article = Article::first();

    (new DeleteArticle())(article: $article);

    $this->assertSoftDeleted($article->refresh());
});

it('removes the tweet from twitter', function () {
    /** @var Article $article */
    $article = Article::first();

    $twitter = $this->mock(Twitter::class);
    $twitter->shouldReceive('post')->once()->andReturn(['id' => $tweetId = fake()->uuid()]);
    $twitter->shouldReceive('removeTweet')->once()->andReturnTrue();

    $this->app->when([ArticleProjector::class])
        ->needs(Twitter::class)
        ->give(fn () => $twitter);

    $article = (new UpdateArticle())(article: $article, articleData: new ArticleData(
        ...[
            'uuid' => '9f16f32e-0ebc-4265-9bf9-4c477edbcda2',
            'title' => 'Why Laravel is Awesome',
            'slug' => 'why-laravel-is-awesome',
            'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget odio pretium dolor sodales tempor in id sapien.',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget odio pretium dolor sodales tempor in id sapien.',
            'published' => true,
            'featuredImage' => '',
        ],
    ));

    (new DeleteArticle())(article: $article);
});

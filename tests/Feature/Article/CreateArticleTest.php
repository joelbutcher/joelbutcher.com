<?php

use App\Domains\Article\Events\ArticleWasCreated;
use App\Domains\Article\Projections\Article;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

beforeEach(fn () => createArticle());

it('stores the event', function () {
    $this->assertDatabaseCount(new EloquentStoredEvent(), 1);
    $this->assertDatabaseHas(new EloquentStoredEvent(), [
        [
            ['event_class' => ArticleWasCreated::class],
        ],
    ]);
});

it('creates an article', function () {
    $this->assertDatabaseHas(new Article(), [
        'uuid' => '9f16f32e-0ebc-4265-9bf9-4c477edbcda2',
        'title' => 'Why Laravel is Good',
        'slug' => 'why-laravel-is-good',
        'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget odio pretium dolor sodales tempor in id sapien.',
        'content' => json_encode(['Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget odio pretium dolor sodales tempor in id sapien.']),
        'published' => false,
    ]);
});

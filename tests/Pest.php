<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use App\Domains\Article\Actions\CreateArticle;
use App\Domains\Article\DataTransferObjects\ArticleData;
use App\Domains\Article\Projections\Article;

uses(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function createArticle(): Article
{
    $data = [
        'uuid' => '9f16f32e-0ebc-4265-9bf9-4c477edbcda2',
        'title' => 'Why Laravel is Good',
        'slug' => 'why-laravel-is-good',
        'excerpt' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget odio pretium dolor sodales tempor in id sapien.',
        'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis eget odio pretium dolor sodales tempor in id sapien.',
        'published' => false,
        'featuredImage' => '',
    ];

    return (new CreateArticle())(
        articleData: new ArticleData(
            ...$data,
        ),
    );
}

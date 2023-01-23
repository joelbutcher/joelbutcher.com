<?php

namespace Database\Factories;

use App\Domains\Article\Projections\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domains\Article\Projections\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * @var class-string<Article>
     */
    protected $model = Article::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'title' => $title = $this->faker->sentence(),
            'content' => $this->faker->paragraphs(),
            'slug' => Str::slug($title),
        ];
    }
}

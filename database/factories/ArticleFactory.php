<?php

namespace Database\Factories;

use App\Concerns\SupportsProjections;
use App\Domains\Article\Projections\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    use SupportsProjections;

    protected $model = Article::class;

    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'title' => $title = $this->faker->sentence(),
            'excerpt' => $this->faker->paragraph(),
            'content' => $this->faker->paragraphs(asText: true),
            'slug' => Str::slug($title),
        ];
    }
}

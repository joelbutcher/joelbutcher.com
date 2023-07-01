<?php

namespace App\Contracts;

use App\Domains\Article\DTOs\ArticleData;

interface PostsArticles
{
    public function postArticle(ArticleData $articleData): void;
}

<?php

namespace App\Domains\Article\Enums;

enum ArticleStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
}

<?php

namespace App\Domains\Article\Enums;

enum ArticleStatus: string
{
    case Unshared = 'unshared';
    case Shared = 'shared';
}

<?php

namespace App\Enums;

enum CollectionHandle: string
{
    case Articles = 'articles';
    case Pages = 'pages';
    case Projects = 'project';
    case Uses = 'uses';
}

<?php

namespace App\Domains\Article\Exceptions;

use App\Support\DomainException;

class ArticleException extends DomainException
{
    public static function articleCannotBeShared(string $articleUuid, ?string $reason): self
    {
        return new self(sprintf(
            "Article [$articleUuid] cannot be shared%s",
            $reason ? "$reason." : '.'
        ));
    }
}

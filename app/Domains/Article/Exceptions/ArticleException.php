<?php

namespace App\Domains\Article\Exceptions;

use App\Support\DomainException;

class ArticleException extends DomainException
{
    public static function articleCannotBePublished(string $uuid, ?string $reason): self
    {
        $reason = $reason ? rtrim($reason, '.') : null;

        return new self(sprintf(
            "Article [$uuid] cannot be shared%s",
            self::formatReason($reason)
        ));
    }

    public static function tweetCannotBeSent(string $uuid, string $reason): self
    {
        return new self(sprintf(
            "Could not sent tweet for article [$uuid]%s",
            self::formatReason($reason)
        ));
    }

    private static function formatReason(?string $reason): string
    {
        if (! $reason) {
            return '.';
        }

        return rtrim(" $reason", '.').'.';
    }
}

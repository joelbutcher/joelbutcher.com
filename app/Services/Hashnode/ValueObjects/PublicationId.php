<?php

namespace App\Services\Hashnode\ValueObjects;

use Stringable;

final readonly class PublicationId implements Stringable
{
    public string $id;

    private function __construct()
    {
        $this->id = strval(config('services.hashnode.publication_id'));
    }

    public static function new(): self
    {
        return new self();
    }

    public function toString(): string
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}

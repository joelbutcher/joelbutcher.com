<?php

namespace App\Domains\Article\Enums;

enum Platform: string
{
    case Hashnode = 'hashnode';
    case DevTo = 'devto';

    public function isEnabled(): bool
    {
        return config("services.$this->value.enabled");
    }
}

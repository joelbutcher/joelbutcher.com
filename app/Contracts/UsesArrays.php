<?php

namespace App\Contracts;

use Illuminate\Contracts\Support\Arrayable;

interface UsesArrays extends Arrayable
{
    public static function fromArray(array $data): self;
}

<?php

namespace App\Concerns;

trait SupportsProjections
{
    public function newModel(array $attributes = [])
    {
        return parent::newModel($attributes)->writeable();
    }
}

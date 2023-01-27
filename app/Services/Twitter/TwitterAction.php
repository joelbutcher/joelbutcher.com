<?php

namespace App\Services\Twitter;

interface TwitterAction
{
    public function apiPath(): string;

    public function requestBody(): array;
}

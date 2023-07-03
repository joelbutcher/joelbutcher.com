<?php

namespace App\Http\Integrations\DevTo\Responses;

use Saloon\Http\Response;

class PublishArticleResponse extends Response
{
    public function id(): int
    {
        return intval($this->json('id'));
    }

    public function title(): string
    {
        return strval($this->json('title'));
    }

    public function slug(): string
    {
        return strval($this->json('slug'));
    }
}

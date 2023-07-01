<?php

namespace App\Http\Integrations\Twitter\Responses;

use Saloon\Http\Response;

class SendTweetResponse extends Response
{
    public function id(): string
    {
        return $this->json('data.id');
    }

    public function content(): string
    {
        return $this->json('data.text');
    }
}

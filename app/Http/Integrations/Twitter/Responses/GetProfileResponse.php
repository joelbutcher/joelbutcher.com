<?php

namespace App\Http\Integrations\Twitter\Responses;

use Saloon\Http\Response;

class GetProfileResponse extends Response
{
    public function id(): string
    {
        return $this->json('data.id');
    }

    public function name(): string
    {
        return $this->json('data.name');
    }

    public function username(): string
    {
        return $this->json('data.username');
    }
}

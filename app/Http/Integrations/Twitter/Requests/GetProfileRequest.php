<?php

namespace App\Http\Integrations\Twitter\Requests;

use App\Http\Integrations\Twitter\Responses\GetProfileResponse;
use Saloon\Enums\Method;

class GetProfileRequest extends TwitterRequest
{
    protected Method $method = Method::GET;

    protected ?string $response = GetProfileResponse::class;

    public function resolveEndpoint(): string
    {
        return '/users/me';
    }
}

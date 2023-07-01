<?php

namespace App\Http\Integrations\Twitter\Requests;

use App\Services\Twitter\DTOs\Tweet;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class DeleteTweetRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::DELETE;

    public function __construct(
        private readonly Tweet $tweet,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return "/tweets/{$this->tweet->id}";
    }
}

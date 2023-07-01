<?php

namespace App\Http\Integrations\Twitter\Requests;

use App\Http\Integrations\Twitter\Responses\SendTweetResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class SendTweetRequest extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    protected ?string $response = SendTweetResponse::class;

    public function __construct(
        private readonly string $content,
    ) {
    }

    public function resolveEndpoint(): string
    {
        return '/tweets';
    }

    protected function defaultBody(): array
    {
        return [
            'text' => $this->content,
        ];
    }
}

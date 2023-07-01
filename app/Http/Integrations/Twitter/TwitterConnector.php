<?php

namespace App\Http\Integrations\Twitter;

use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Saloon\Contracts\PendingRequest as PendingRequestContract;
use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class TwitterConnector extends Connector
{
    use AcceptsJson;

    public function __construct(
        private readonly Oauth1 $oauth1,
    ) {
    }

    public function boot(PendingRequestContract $pendingRequest): void
    {
        $pendingRequest->getSender()->addMiddleware($this->oauth1);
    }

    public static function baseUrl(): string
    {
        return 'https://api.twitter.com/2';
    }

    public function resolveBaseUrl(): string
    {
        return self::baseUrl();
    }

    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
        ];
    }

    protected function defaultConfig(): array
    {
        return [
            'auth' => 'oauth',
        ];
    }
}

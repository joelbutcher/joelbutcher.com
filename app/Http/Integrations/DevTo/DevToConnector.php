<?php

namespace App\Http\Integrations\DevTo;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class DevToConnector extends Connector
{
    use AcceptsJson;

    public function __construct(
        protected string $apiToken,
    ) {
        $this->headers()->add('api-key', $this->apiToken);
    }

    public function resolveBaseUrl(): string
    {
        return config('services.devto.base_url');
    }

    protected function defaultHeaders(): array
    {
        return [
            'Accept' => 'application/vnd.forem.api-v1+json',
            'Content-Type' => 'application/json',
        ];
    }
}

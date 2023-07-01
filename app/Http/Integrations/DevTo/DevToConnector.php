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
        $this->withTokenAuth($this->apiToken, 'api-key');
    }

    public function resolveBaseUrl(): string
    {
        return config('services.devto.base_url');
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }
}

<?php

namespace App\Http\Integrations\Hashnode;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class HashnodeConnector extends Connector
{
    use AcceptsJson;

    public function __construct(
        protected string $apiToken,
    ) {
        $this->withTokenAuth($this->apiToken, prefix: '');
    }

    public function resolveBaseUrl(): string
    {
        return config('services.hashnode.base_url');
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }
}

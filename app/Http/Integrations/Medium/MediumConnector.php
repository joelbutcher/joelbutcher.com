<?php

namespace App\Http\Integrations\Medium;

use Saloon\Http\Connector;
use Saloon\Traits\Plugins\AcceptsJson;

class MediumConnector extends Connector
{
    use AcceptsJson;

    public function __construct(
        protected string $apiToken,
        protected string $baseUrl,
    ) {
        $this->withTokenAuth($this->apiToken);
    }

    public function resolveBaseUrl(): string
    {
        return $this->baseUrl;
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
        ];
    }
}

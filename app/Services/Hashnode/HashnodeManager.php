<?php

namespace App\Services\Hashnode;

use App\Http\Integrations\Hashnode\HashnodeConnector;

final readonly class HashnodeManager
{
    public function __construct(
        private HashnodeConnector $connector,
    ) {
    }
}

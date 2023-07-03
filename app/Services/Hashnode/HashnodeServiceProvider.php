<?php

namespace App\Services\Hashnode;

use App\Http\Integrations\Hashnode\HashnodeConnector;
use Illuminate\Support\ServiceProvider;

class HashnodeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(HashnodeConnector::class, fn () => new HashnodeConnector(
            apiToken: config('services.hashnode.token'),
        ));

        $this->app->alias(HashnodeService::class, 'hashnode');
        $this->app->alias(HashnodeConnector::class, 'hashnode.connector');
    }
}

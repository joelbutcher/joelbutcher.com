<?php

namespace App\Services\DevTo;

use App\Http\Integrations\DevTo\DevToConnector;
use Illuminate\Support\ServiceProvider;

class DevToServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DevToConnector::class, fn () => new DevToConnector(
            apiToken: config('services.devto.token'),
        ));

        $this->app->alias(DevToService::class, 'devto');
        $this->app->alias(DevToConnector::class, 'devto.connector');
    }
}

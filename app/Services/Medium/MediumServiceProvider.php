<?php

namespace App\Services\Medium;

use App\Http\Integrations\Medium\MediumConnector;
use App\Services\Medium\Facades\Medium;
use Illuminate\Support\ServiceProvider;

class MediumServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            abstract: MediumConnector::class,
            concrete: fn () => new MediumConnector(
                apiToken: config('services.medium.token'),
                baseUrl: config('services.medium.base_url'),
            ),
        );

        $this->app->alias(Medium::class, '\Medium');
        $this->app->alias(MediumManager::class, 'medium');
    }
}

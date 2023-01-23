<?php

namespace App\Providers;

use App\Listeners\CreateOrUpdateArticleListener;
use App\Listeners\DeleteArticleListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Statamic\Events\EntryCreated;
use Statamic\Events\EntryDeleted;
use Statamic\Events\EntrySaved;

class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        EntryCreated::class => [
            CreateOrUpdateArticleListener::class,
        ],
        EntrySaved::class => [
            CreateOrUpdateArticleListener::class,
        ],
        EntryDeleted::class => [
            DeleteArticleListener::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}

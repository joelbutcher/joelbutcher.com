<?php

namespace App\Providers;

use App\Listeners\DeleteArticleListener;
use App\Listeners\SaveArticleListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
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
        EntrySaved::class => [
            SaveArticleListener::class,
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

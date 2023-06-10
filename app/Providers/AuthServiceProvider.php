<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Validation\UnauthorizedException;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Statamic\Facades\OAuth;
use Statamic\Facades\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        OAuth::provider('github')->withUser(function (SocialiteUser $socialite) {
            if (! str($socialite->getEmail())->contains(['joelbutcher', 'joel167897'], ignoreCase: true)) {
                throw new UnauthorizedException('You are not authorized to access this site.');
            }

            return User::make()
                ->email($socialite->getEmail())
                ->data(OAuth::provider('github')->userData($socialite));
        });
    }
}

<?php

return [

    'enabled' => env('STATAMIC_OAUTH_ENABLED', false),

    'email_login_enabled' => false,

    'providers' => [
        'github' => 'GitHub',
    ],

    'routes' => [
        'login' => 'oauth/{provider}',
        'callback' => 'oauth/{provider}/callback',
    ],

    /*
    |--------------------------------------------------------------------------
    | Remember Me
    |--------------------------------------------------------------------------
    |
    | Whether or not the "remember me" functionality should be used when
    | authenticating using OAuth. When enabled, the user will remain
    | logged in indefinitely, or until they manually log out.
    |
    */

    'remember_me' => true,

];

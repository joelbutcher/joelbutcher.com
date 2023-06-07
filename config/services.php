<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_REDIRECT', 'https://joelbutcher.com.test/oauth/github/callback'),
    ],

    'twitter' => [
        'enabled' => env('TWITTER_ENABLED'),
        'client_id' => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect' => env('TWITTER_REDIRECT', 'https://joelbutcher.com.test/oauth/twitter-oauth-2/callback'),

        'consumer_key' => env('TWITTER_CONSUMER_KEY', 'my-twitter-consumer-key'),
        'consumer_secret' => env('TWITTER_CONSUMER_SECRET', 'my-twitter-consumer-secret'),
        'access_token' => env('TWITTER_ACCESS_TOKEN', 'my-twitter-access-token'),
        'access_secret' => env('TWITTER_ACCESS_SECRET', 'my-twitter-access-secret'),
    ],
];

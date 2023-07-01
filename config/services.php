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

        'consumer_key' => env('TWITTER_CONSUMER_KEY'),
        'consumer_secret' => env('TWITTER_CONSUMER_SECRET'),
        'access_token' => env('TWITTER_ACCESS_TOKEN'),
        'access_secret' => env('TWITTER_ACCESS_SECRET'),
    ],

    'medium' => [
        'enabled' => env('MEDIUM_ENABLED'),
        'base_url' => env('MEDIUM_API_BASE_URL', 'https://api.medium.com/v1'),
        'token' => env('MEDIUM_API_TOKEN'),
        'user_id' => env('MEDIUM_USER_ID'),
    ],

    'hashnode' => [
        'enabled' => env('HASHNODE_ENABLED'),
        'base_url' => env('HASHNODE_API_BASE_URL', 'https://api.hashnode.com'),
        'token' => env('HASHNODE_API_TOKEN'),
    ],

    'devto' => [
        'enabled' => env('DEVTO_ENABLED'),
        'base_url' => env('DEVTO_API_BASE_URL', 'https://dev.to/api'),
        'token' => env('DEVTO_API_TOKEN'),
    ],
];

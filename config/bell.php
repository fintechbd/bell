<?php

// config for Fintech/Bell
use Fintech\Bell\Drivers\FirebasePush;
use Fintech\Bell\Drivers\WebPush;

return [

    /*
    |--------------------------------------------------------------------------
    | Enable Module APIs
    |--------------------------------------------------------------------------
    | this setting enable the api will be available or not
    */
    'enabled' => env('PACKAGE_BELL_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Bell Group Root Prefix
    |--------------------------------------------------------------------------
    |
    | This value will be added to all your routes from this package
    | Example: APP_URL/{root_prefix}/api/bell/action
    |
    | Note: while adding prefix add closing ending slash '/'
    */

    'root_prefix' => 'api/',

    /*
    |--------------------------------------------------------------------------
    | Notification Channels Vendor Configuration
    |--------------------------------------------------------------------------
    |
    | This value will be added to all your routes from this package
    | Example: APP_URL/{root_prefix}/api/bell/action
    |
    | Note: while adding prefix add closing ending slash '/'
    */
    'push' => [
        'mode' => 'sandbox',
        'default' => 'fcm',
        'fcm' => [
            'driver' => FirebasePush::class,
            'live' => [
                'url' => 'https://fcm.googleapis.com/v1/projects/{project_id}/messages:send',
                'token' => null,
                'expired_at' => null,
                'json' => env('PACKAGE_BELL_FIREBASE_CREDENTIALS'),
            ],
            'sandbox' => [
                'url' => 'https://fcm.googleapis.com/v1/projects/{project_id}/messages:send',
                'token' => null,
                'expired_at' => null,
                'json' => env('PACKAGE_BELL_FIREBASE_CREDENTIALS'),
            ],
        ],
        'web' => [
            'driver' => WebPush::class,
            'live' => [
                'url' => 'https://mmk314.api.infobip.com/sms/2/text/advanced',
                'username' => null,
                'password' => null,
                'from' => null,
            ],
            'sandbox' => [
                'url' => 'https://mmk314.api.infobip.com/sms/2/text/advanced',
                'username' => null,
                'password' => null,
                'from' => null,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Template Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'template_model' => \Fintech\Bell\Models\Template::class,

    // ** Model Config Point Do not Remove **//
    /*
    |--------------------------------------------------------------------------
    | Repositories
    |--------------------------------------------------------------------------
    |
    | This value will be used across systems where a repository instance is needed
    */

    'repositories' => [
        \Fintech\Bell\Interfaces\TemplateRepository::class => \Fintech\Bell\Repositories\Eloquent\TemplateRepository::class,

        // ** Repository Binding Config Point Do not Remove **//
    ],

];

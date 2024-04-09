<?php

// config for Fintech/Bell
use Fintech\Bell\Drivers\FirebasePush;
use Fintech\Bell\Models\NotificationTemplate;
use Fintech\Bell\Models\Trigger;
use Fintech\Bell\Models\TriggerAction;
use Fintech\Bell\Models\TriggerRecipient;
use Fintech\Bell\Models\TriggerVariable;
use Fintech\Bell\Repositories\Eloquent\NotificationTemplateRepository;
use Fintech\Bell\Repositories\Eloquent\TriggerActionRepository;
use Fintech\Bell\Repositories\Eloquent\TriggerRecipientRepository;
use Fintech\Bell\Repositories\Eloquent\TriggerRepository;
use Fintech\Bell\Repositories\Eloquent\TriggerVariableRepository;

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

    'root_prefix' => 'test/',

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
                'url' => 'https://fcm.googleapis.com/fcm/send',
                'username' => null,
                'password' => null,
                'from' => null,
            ],
            'sandbox' => [
                'url' => 'https://fcm.googleapis.com/fcm/send',
                'username' => null,
                'password' => null,
                'from' => null,
            ],
        ],
        'web' => [
            'driver' => \Fintech\Bell\Drivers\WebPush::class,
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
    | Trigger Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'trigger_model' => Trigger::class,

    /*
    |--------------------------------------------------------------------------
    | Trigger Recipient Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'trigger_recipient_model' => TriggerRecipient::class,

    /*
    |--------------------------------------------------------------------------
    | Trigger Variable Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'trigger_variable_model' => TriggerVariable::class,

    /*
    |--------------------------------------------------------------------------
    | Notification Template Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'notification_template_model' => NotificationTemplate::class,

    /*
    |--------------------------------------------------------------------------
    | TriggerAction Model
    |--------------------------------------------------------------------------
    |
    | This value will be used to across system where model is needed
    */
    'trigger_action_model' => TriggerAction::class,

    //** Model Config Point Do not Remove **//
    /*
    |--------------------------------------------------------------------------
    | Repositories
    |--------------------------------------------------------------------------
    |
    | This value will be used across systems where a repository instance is needed
    */

    'repositories' => [
        \Fintech\Bell\Interfaces\TriggerRepository::class => TriggerRepository::class,

        \Fintech\Bell\Interfaces\TriggerRecipientRepository::class => TriggerRecipientRepository::class,

        \Fintech\Bell\Interfaces\TriggerVariableRepository::class => TriggerVariableRepository::class,

        \Fintech\Bell\Interfaces\NotificationTemplateRepository::class => NotificationTemplateRepository::class,

        \Fintech\Bell\Interfaces\TriggerActionRepository::class => TriggerActionRepository::class,

        //** Repository Binding Config Point Do not Remove **//
    ],

];

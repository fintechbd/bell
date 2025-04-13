<?php

namespace Fintech\Bell\Drivers;

use Fintech\Bell\Abstracts\PushDriver;
use Fintech\Bell\Messages\PushMessage;
use Fintech\Bell\Messages\SmsMessage;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

/**
 * @see https://laravel-notification-channels.com/webpush/
 */
class WebPush extends PushDriver
{
    private array $config;

    public function __construct()
    {
        $mode = config('fintech.bell.sms.mode', 'sandbox');

        $this->config = config("fintech.bell.sms.clicksend.{$mode}", [
            'url' => null,
            'username' => null,
            'password' => null,
        ]);
    }

    public function send(PushMessage $message): Response
    {
        $this->validate($message);

        $payload = ['messages' => [[
            'source' => 'php',
            'body' => $message->getContent(),
            'to' => $message->getReceiver(),
        ]]];

        $response = Http::withoutVerifying()
            ->timeout(30)
            ->contentType('application/json')
            ->withBasicAuth($this->config['username'], $this->config['password'])
            ->post($this->config['url'], $payload)->json();

        logger('Web Push Response', [$response]);

        return $response;
    }

    public function validate(PushMessage $message): void
    {
        // TODO: Implement validate() method.
    }
}

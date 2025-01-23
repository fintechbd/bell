<?php

namespace Fintech\Bell\Drivers;

use Carbon\CarbonImmutable;
use Fintech\Bell\Abstracts\PushDriver;
use Fintech\Bell\Messages\PushMessage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Http;

/**
 * FCM (Firebase Cloud Messaging)
 *
 * @see https://laravel-notification-channels.com/fcm/
 */
class FirebasePush extends PushDriver
{
    private array $config = [];

    private array $credentials = [];

    /**
     * @throws FileNotFoundException
     */
    public function __construct()
    {
        $mode = config('fintech.bell.push.mode', 'sandbox');

        $this->config = config("fintech.bell.push.fcm.{$mode}", [
            'url' => null,
            'token' => null,
            'expired_at' => null,
            'json' => null,
        ]);

        $json_path = base_path($this->config['json']);

        if (! is_file($json_path)) {
            throw new FileNotFoundException("File not found at {$json_path}");
        }

        $this->credentials = json_decode(file_get_contents($json_path), true);

        $this->refreshToken();
    }

    private function refreshToken(): void
    {
        $expiredAt = $this->config['expired_at'] ?? null;

        if ($expiredAt == null || now()->gt(CarbonImmutable::parse($expiredAt))) {

            $payload = [

            ];

            $response = Http::withoutVerifying()
                ->timeout(30)
                ->contentType('application/json')
                ->withToken($this->config['token'])
                ->post(str_replace(['{project_id}'], [$this->credentials['project_id']], $this->config['url']), $payload)
                ->json();
        }
    }

    public function send(PushMessage $message): void
    {
        $this->validate($message);

        $payload = [
            'message' => [
                'token' => 'token',
                ...$message->getPayload(),
            ],
        ];

        $payloadJson = json_encode($payload);

        if (strlen($payloadJson) > 4096) {
            throw new \OverflowException('Payload size is ' (strlen($payloadJson) / 1024).'KB is over the 4KB limit.');
        }

        $response = Http::withoutVerifying()
            ->timeout(30)
            ->contentType('application/json')
            ->withToken($this->config['token'])
            ->post(str_replace(['{project_id}'], [$this->credentials['project_id']], $this->config['url']), $payload)
            ->json();

        logger('PUsh Response', [$response]);
    }
}

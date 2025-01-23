<?php

namespace Fintech\Bell\Drivers;

use Carbon\CarbonImmutable;
use Fintech\Bell\Abstracts\PushDriver;
use Fintech\Bell\Messages\PushMessage;
use Fintech\Core\Facades\Core;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class FirebasePush extends PushDriver
{
    private array $config = [];

    private array $credentials = [];

    private string $mode;

    /**
     * @throws FileNotFoundException
     * @throws \Exception
     */
    public function __construct()
    {
        $this->mode = config('fintech.bell.push.mode', 'sandbox');

        $this->config = config("fintech.bell.push.fcm.{$this->mode}", [
            'url' => null,
            'access_token' => null,
            'expired_at' => null,
            'json' => null,
        ]);

        $json_path = base_path($this->config['json']);

        if (!is_file($json_path)) {
            throw new FileNotFoundException("File not found at {$json_path}");
        }

        $this->credentials = json_decode(file_get_contents($json_path), true);

        $this->refreshToken();
    }

    /**
     * @throws \Exception
     */
    private function refreshToken(): void
    {
        $expiredAt = $this->config['expired_at'] ?? null;

        if ($expiredAt == null || now()->gt(CarbonImmutable::parse($expiredAt))) {
            // Create the JWT
            $header = $this->base64url_encode(json_encode(["alg" => "RS256", "typ" => "JWT"]));
            $assertion = $this->base64url_encode(json_encode([
                "iss" => $this->credentials['client_email'],
                "scope" => 'https://www.googleapis.com/auth/firebase.messaging',
                "aud" => $this->credentials['token_uri'],
                "exp" => time() + HOUR,
                "iat" => time()
            ]));
            $signature = '';
            if (extension_loaded('openssl')) {
                openssl_sign($header . '.' . $assertion, $signature, $this->credentials['private_key'], 'sha256');
            }

            $payload = [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion' => $header . '.' . $assertion . '.' . $this->base64url_encode($signature)
            ];

            $response = Http::withoutVerifying()
                ->asForm()
                ->post($this->credentials['token_uri'], $payload)
                ->json();

            $newExpireAt = now()->addSeconds($response['expires_in'])->format('Y-m-d H:i:s');

            $this->config['expired_at'] = $newExpireAt;
            $this->config['access_token'] = $response['access_token'];

            Core::setting()->setValue('bell',"push.fcm.{$this->mode}.access_token", $response['access_token']);
            Core::setting()->setValue('bell',"push.fcm.{$this->mode}.expired_at", $newExpireAt);
        }
    }

    public function validate(PushMessage $message): void
    {
        if (empty($message->getPayload('token'))) {
            throw new InvalidArgumentException('Push Notification Token is empty.');
        }

        if (empty($message->getPayload('notification.title'))) {
            throw new InvalidArgumentException('Push Notification Title is empty.');
        }

        if (empty($message->getPayload('notification.body'))) {
            throw new InvalidArgumentException('Push Notification Body is empty.');
        }
    }

    public function send(PushMessage $message): Response
    {
        $this->validate($message);

        $payload = [
            'message' => $message->getPayload(),
        ];

        $payloadJson = json_encode($payload);

        if (strlen($payloadJson) > 4096) {
            throw new \OverflowException('Payload size is ' (strlen($payloadJson) / 1024) . 'KB is over the 4KB limit.');
        }

        return Http::withoutVerifying()
            ->contentType('application/json')
            ->withToken($this->config['access_token'])
            ->post(str_replace(['{project_id}'], [$this->credentials['project_id']], $this->config['url']), $payload);
    }

    private function base64url_encode($data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}

<?php

namespace Fintech\Bell\Messages;

class PushMessage
{
    private array $payload = [
        'token' => null,
        'notification' => [
            'title' => null,
            'type' => null,
            'body' => null,
            'image' => null,
        ],
        'data' => [
            'title' => null,
            'type' => null,
            'body' => null,
            'image' => null,
            'data' => [],
        ],
    ];

    public function getPayload(?string $key = null): mixed
    {
        if ($key != null) {
            return data_get($this->payload, $key);
        }

        return $this->payload;
    }

    public function type(string $type): self
    {
        $this->payload['notification']['type'] = $type;
        $this->payload['data']['type'] = $type;

        return $this;
    }

    public function title(string $title): self
    {
        $this->payload['notification']['title'] = $title;
        $this->payload['data']['title'] = $title;

        return $this;
    }

    public function body(string $body): self
    {
        $this->payload['notification']['body'] = $body;
        $this->payload['data']['body'] = $body;

        return $this;
    }

    public function image(?string $image = null): self
    {
        if ($image) {
            $this->payload['notification']['image'] = $image;
            $this->payload['data']['image'] = $image;
        }

        return $this;
    }

    public function data(mixed $data = []): self
    {
        $this->payload['data']['data'] = $data;

        return $this;
    }

    public function token($token): self
    {
        $this->payload['token'] = $token;

        return $this;
    }
}

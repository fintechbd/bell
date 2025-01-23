<?php

namespace Fintech\Bell\Abstracts;

use Fintech\Bell\Messages\PushMessage;

abstract class PushDriver
{
    abstract public function validate(PushMessage $message): void;

    abstract public function send(PushMessage $message): \Illuminate\Http\Client\Response;
}

<?php

namespace Zing\LaravelSms\Contracts;

use JsonSerializable;

interface Message extends JsonSerializable
{
    public const VOICE = 'voice';

    public const TEXT = 'text';

    public function getContent($driver = null): ?string;

    public function getTemplate($driver = null): ?string;

    public function getData($driver = null): ?array;

    public function __toString();
}

<?php


namespace Zing\LaravelSms\Contracts;

use JsonSerializable;

interface Message extends JsonSerializable
{
    public const VOICE = 'voice';
    public const TEXT  = 'text';

    public function getContent($gateway = null): ?string;


    public function getTemplate($gateway = null): ?string;


    public function getData($gateway = null): ?array;
    public function __toString();
}
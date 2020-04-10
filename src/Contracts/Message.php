<?php


namespace Zing\LaravelSms\Contracts;


interface Message
{
    public const VOICE = 'voice';
    public const TEXT  = 'text';

    public function getContent($gateway = null): string;


    public function getTemplate($gateway = null): string;


    public function getData($gateway = null): array;
}
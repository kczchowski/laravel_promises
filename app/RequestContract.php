<?php

namespace App;


interface RequestContract
{

    static public function isValid(string $request): bool;

    public function hasResponse(): bool;

    public function getResponse();

    public function getPacket(): string;

}

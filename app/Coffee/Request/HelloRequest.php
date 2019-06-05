<?php

namespace App\Coffee\Request;

use App\RequestContract;

class HelloRequest implements RequestContract
{

    protected $packet;

    protected $id;

    public function __construct(string $packet)
    {
        $this->setPacket($packet);
    }

    public function getPacket(): string
    {
        return $this->packet;
    }

    protected function setPacket(string $packet)
    {
        $this->packet = $packet;
        preg_match('/^(?P<id>[0-9]{5});HELLO/', $packet, $out);
        $this->id = $out['id'];
    }

    static public function isValid(string $request): bool
    {
        preg_match('/^(?P<id>[0-9]{5});HELLO/', $request, $out);
        return array_key_exists('id', $out);
    }

    public function hasResponse(): bool
    {
        return true;
    }

    public function getResponse()
    {
        return "{$this->id};WELCOME";
    }

}

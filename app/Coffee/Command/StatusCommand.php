<?php

namespace App\Coffee\Command;

use App\Coffee\Request\StatusRequest;
use App\CommandContract;

class StatusCommand implements CommandContract
{

    protected $payload;

    protected $target;

    public function __construct($target, $payload)
    {
        $this->payload = $payload;
        $this->target = $payload;
    }

    public function resolveUsing(): ?string
    {
        return StatusRequest::class;
    }

}

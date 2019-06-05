<?php

namespace App;

interface CommandContract
{
    public function resolveUsing(): ?string;
}

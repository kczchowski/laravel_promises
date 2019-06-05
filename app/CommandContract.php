<?php

namespace App;

/**
 * Interface CommandContract
 * @package App
 */
interface CommandContract
{

    /**
     * @return string|null
     */
    public function resolveUsing(): ?string;

    /**
     * @return string
     */
    public function getPayload(): string;

}

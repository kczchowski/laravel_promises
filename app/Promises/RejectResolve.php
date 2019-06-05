<?php

namespace App\Promises;

/**
 * Class RejectResolve
 *
 * @package App\Promises
 */
class RejectResolve
{

    /** @var mixed */
    protected $reason;

    /**
     * RejectResolve constructor.
     *
     * @param $reason
     */
    public function __construct($reason)
    {
        $this->reason = $reason;
    }

    /**
     * @return mixed
     */
    public function getReason()
    {
        return $this->reason;
    }

}

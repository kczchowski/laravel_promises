<?php

namespace App\Promises;

use App\RequestContract;

/**
 * Class SuccessResolve
 *
 * @package App\Promises
 */
class SuccessResolve
{

    /** @var \App\RequestContract  */
    protected $request;

    /**
     * SuccessResolve constructor.
     *
     * @param \App\RequestContract $request
     */
    public function __construct(RequestContract $request)
    {
        $this->request = $request;
    }

    /**
     * @return \App\RequestContract
     */
    public function getRequest(): RequestContract
    {
        return $this->request;
    }

}

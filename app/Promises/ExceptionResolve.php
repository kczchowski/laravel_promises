<?php

namespace App\Promises;

/**
 * Class ExceptionResolve
 *
 * @package App\Promises
 */
class ExceptionResolve
{

    /** @var \Exception  */
    protected $exception;

    /**
     * ExceptionResolve constructor.
     *
     * @param \Exception $exception
     */
    public function __construct(\Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * @return mixed
     */
    public function getException(): \Exception
    {
        return $this->exception;
    }

}

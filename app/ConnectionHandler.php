<?php

namespace App;

/**
 * Class ConnectionHandler
 * @package App
 */
class ConnectionHandler
{

    /** @var array  */
    protected $registered;

    /**
     * ConnectionHandler constructor.
     */
    public function __construct()
    {
        $this->registered = [];
    }

    /**
     * @param string   $type
     * @param \Closure $function
     */
    public function register(string $type, \Closure $function)
    {
        if (!array_key_exists($type, $this->registered)) {
            $this->registered[$type] = [];
        }
        $this->registered[$type][] = $function;
    }

    /**
     * @param \App\RequestContract $frame
     */
    public function process(RequestContract $frame)
    {
        $type = get_class($frame);
        if (array_key_exists($type, $this->registered)) {
            $closures = $this->registered[$type];

            foreach ($closures as $closure) {
                $closure($frame);
            }

            $this->clear($type);
        }
    }

    /**
     * @param string $type
     */
    public function clear(string $type)
    {
        unset($this->registered[$type]);
    }

}

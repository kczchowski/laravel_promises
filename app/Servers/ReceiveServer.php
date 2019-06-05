<?php

namespace App\Servers;

use React\EventLoop\LoopInterface;
use React\Socket\Server;
use React\Socket\ConnectionInterface;

class ReceiveServer
{

    protected $loop;

    protected $bindAddress;

    protected $socket;

    /** @var \App\ConnectionPool */
    protected $pool;

    public function __construct(LoopInterface $loop, string $bindAddress)
    {
        $this->loop = $loop;
        $this->bindAddress = $bindAddress;
        $this->pool = app('connection.pool');
    }

    public function start()
    {
        echo "-- TCP/IP on {$this->bindAddress}" . PHP_EOL;
        $this->socket = new Server($this->bindAddress, $this->loop);
        $this->socket->on('connection', $this->handleConnection());
        $this->socket->on('error', $this->handleError());
    }

    protected function lifecycleHooks()
    {

    }

    public function getConnectionPool()
    {
        return $this->pool;
    }

    /**
     * Handle the incoming connections, register them into the connection pool
     * @return \Closure
     */
    public function handleConnection()
    {
        return function (ConnectionInterface $connection) {
            echo "-> Incoming connection from {$connection->getRemoteAddress()}" . PHP_EOL;
            $this->pool->registerConnection($connection);
        };
    }

    /**
     * Handle connection error for the incoming connections
     * @return \Closure
     */
    public function handleError()
    {
        return function (ConnectionInterface $connection) {
            echo "Encountered error with connection {$connection->getRemoteAddress()}" . PHP_EOL;
        };
    }


}

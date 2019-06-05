<?php

namespace App;

use React\Socket\ConnectionInterface;

class ConnectionPool
{

    private $connections;

    public function __construct()
    {
        $this->connections = collect();
    }

    public function registerConnection(ConnectionInterface $incomingConnection): ?Connection
    {
        $remoteAddress = $incomingConnection->getRemoteAddress();

        $connectionWrapper = new Connection($incomingConnection);
        $this->connections->put($remoteAddress, $connectionWrapper);

        return $connectionWrapper;
    }

    public function getConnection(string $remoteAddress): Connection
    {
        return $this->connections->get($remoteAddress);
    }

    public function hasConnection(string $remoteAddress): bool
    {
        return $this->connections->has($remoteAddress);
    }

}

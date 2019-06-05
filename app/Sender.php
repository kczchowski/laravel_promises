<?php

namespace App;

use App\Coffee\Command\StatusCommand;
use App\Promises\RejectResolve;
use React\Promise\Promise;
use Symfony\Component\HttpFoundation\Request;

class Sender
{

    public function send(Request $request): Promise
    {
        return new Promise(function($resolve, $reject) use ($request){
            $target = $request->get('target');

            if($this->pool()->hasConnection($target)){
                $connection = $this->pool()->getConnection($target);
                $command = new StatusCommand($target, $request->get('payload'));
                $connection->send($command);
            }else{
                $reject(new RejectResolve("Unable to locate connection to target"));
            }
        });
    }

    public function pool(): ConnectionPool
    {
        return app()->make('connection.pool');
    }

}

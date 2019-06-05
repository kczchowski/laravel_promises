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
            $body = json_decode($request->getContent());
            $target = $body->target;

            if($this->pool()->hasConnection($target)){
                $connection = $this->pool()->getConnection($target);
                $command = new StatusCommand($target, $body->payload);
                $connection->send($command)->then(function($success) use ($resolve){
                    $resolve($success);
                },function($reject) use ($resolve){
                    $resolve($reject);
                });
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

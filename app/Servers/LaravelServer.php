<?php

namespace App\Servers;

use App\Promises\ExceptionResolve;
use App\Promises\RejectResolve;
use App\Promises\SuccessResolve;
use App\Sender;
use React\Promise\Promise;
use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\LoopInterface;
use React\Socket\Server;
use React\Http\Response;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Request;

class LaravelServer
{

    protected $loop;

    protected $socket;

    protected $server;

    public function __construct(LoopInterface $loop, string $bindAddress)
    {
        echo "-- Laravel on {$bindAddress}" . PHP_EOL;
        $this->loop = $loop;
        $this->socket = new Server($bindAddress, $this->loop);
    }

    public function start()
    {
        $this->server = new \React\Http\Server(
            $this->handleRequest()
        );
        $this->server->listen($this->socket);
    }

    public function handleRequest()
    {
        return function (ServerRequestInterface $incomingRequest) {
            $factory = new HttpFoundationFactory();
            $request = $factory->createRequest($incomingRequest);

            if ($this->handleWithLaravel($incomingRequest)) {
                return $this->laravelHandler($request);
            }
            return $this->customHandler($request);
        };
    }

    protected function laravelHandler(Request $incomingRequest): Promise
    {
        return new Promise(function ($resolve, $reject) use ($incomingRequest) {

            $factory = new HttpFoundationFactory();
            $request = $factory->createRequest($incomingRequest);

            /** @var \Symfony\Component\HttpFoundation\Response $response */
            $appResponse = app()->handle($request);

            $psrBridge = new DiactorosFactory();
            $psrResponse = $psrBridge->createResponse($appResponse);

            $response = new Response(
                $psrResponse->getStatusCode(),
                $psrResponse->getHeaders(),
                $psrResponse->getBody(),
                $psrResponse->getProtocolVersion(),
                $psrResponse->getReasonPhrase()
            );

            $resolve($response);
        });
    }

    protected function handleWithLaravel(ServerRequestInterface $request): bool
    {
        return ('/send' !== $request->getRequestTarget());
    }

    protected function customHandler(Request $incomingRequest): Promise
    {
        $sender = new Sender();
        return new Promise(function ($resolve, $reject) use ($sender, $incomingRequest) {
            $innerPromise = $sender->send($incomingRequest);
            $innerPromise->then(function ($reason) use ($resolve) {
                if($reason instanceof SuccessResolve){
                    $response = $this->makeSuccessResponse([
                        'status' => 'ok',
                        'response' => $reason->getRequest()
                    ]);
                }else{
                    $response = $this->makeSuccessResponse([
                        'status' => 'ok'
                    ]);
                }
                $resolve($response);
            }, function ($reason) use ($resolve) {
                if ($reason instanceof ExceptionResolve) {
                    $response = $this->makeRejectResponse([
                        'reason' => $reason->getException()->getMessage(),
                        'file' => $reason->getException()->getFile(),
                        'line' => $reason->getException()->getLine()
                    ]);
                } elseif ($reason instanceof RejectResolve) {
                    $response = $this->makeRejectResponse(['reason' => $reason->getReason()]);
                } else {
                    $response = $this->makeRejectResponse(['status' => 'Ups!']);
                }
                $resolve($response);
            });
        });
    }

    protected function makeRejectResponse(array $body, int $code = 400, $httpReason = "Bad Request"): Response
    {
        return new Response(
            $code,
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            json_encode($body),
            '1.1',
            $httpReason
        );
    }

    protected function makeSuccessResponse(array $body, int $code = 200): Response
    {
        return new Response(
            $code,
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            json_encode($body),
            '1.1',
            'OK'
        );
    }

}

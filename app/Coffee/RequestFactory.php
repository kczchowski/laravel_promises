<?php

namespace App\Coffee;

use App\Coffee\Request\HelloRequest;
use App\Coffee\Request\StatusRequest;
use App\RequestContract;

class RequestFactory
{

    private $availableRequests;

    public function __construct()
    {
        $this->availableRequests = collect([
            HelloRequest::class,
            StatusRequest::class
        ]);
    }

    public function make(string $packet): ?RequestContract
    {
        $requestClass = $this->availableRequests->first(function(string $requestClass) use ($packet){
            return $requestClass::isValid($packet);
        });

        if($requestClass !== null){
            return new $requestClass($packet);
        }

        return null;
    }

}

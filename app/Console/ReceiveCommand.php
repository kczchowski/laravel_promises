<?php

namespace App\Console;

use App\Servers\LaravelServer;
use App\Servers\ReceiveServer;
use Illuminate\Console\Command;

class ReceiveCommand extends Command
{

    protected $signature = "tcp:receive {--l|listen=tcp://0.0.0.0} {--p|port=11000} {--c|control=12000}";

    protected $description = "Receive TCP packets";

    protected $listenPort = 11000;

    protected $controlPort = 12000;

    protected $listenAddress = "tcp://0.0.0.0";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->init();

        $loop = app('react.loop');
        $server = new ReceiveServer($loop, $this->listenAddress . ':' . $this->listenPort);
        $laravel = new LaravelServer($loop, $this->listenAddress . ':' . $this->controlPort);
        $server->start();
        $laravel->start();

        $loop->run();
    }

    private function init()
    {
        $this->listenPort = $this->option('port');
        $this->controlPort = $this->option('control');
        $this->listenAddress = $this->option('listen');
    }
}

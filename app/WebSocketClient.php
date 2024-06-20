<?php

namespace App;

use React\EventLoop\Loop;
use React\Socket\Connector as ReactConnector;
use Ratchet\Client\Connector as RatchetConnector;

class WebSocketClient
{
    private string $host;
    private int $port;
    protected $loop;
    private ReactConnector $reactConnector;
    private RatchetConnector $ratchetConnector;

    /**
     * Create a new class instance.
     */
    public function __construct(string $host, int $port)
    {
        $this->host = $host;
        $this->port = $port;

        $this->loop = Loop::get();
        $this->reactConnector = new ReactConnector();
        $this->ratchetConnector = new RatchetConnector($this->loop, $this->reactConnector);
    }

    public function run()
    {
        $connector = $this->ratchetConnector;
        $connector('ws://Renaud2-PC:8080')->then(function ($conn) {
            $conn->on('message', function ($msg) {
                echo "Received: {$msg}\n";
            });

            $conn->on('close', function ($code = null, $reason = null) {
                echo "Connection closed ({$code} - {$reason})\n";
            });

            $conn->send('Hello World!');
        }, function ($e) {
            echo "Could not connect: {$e->getMessage()}\n";
        });
    }
}

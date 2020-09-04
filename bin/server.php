<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Mafia\Mafia;

require dirname(__DIR__) . '/vendor/autoload.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Mafia()
        )
    ),
    3000
);

echo "listening on *: 3000\n";

$server->run();

echo "Server has been disconnected.\n";
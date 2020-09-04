<?php
use Ratchet\Server\IoServer;
use MyApp\Chat;

require dirname(__DIR__) . '/vendor/autoload.php';

$server = IoServer::factory(
    new Mafia(),
    3000
);

$server->run();
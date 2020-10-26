<?php

require '../vendor/voryx/thruway/Examples/bootstrap.php';

use Thruway\Peer\Router;
use Thruway\Transport\RatchetTransportProvider;

$router = new Router();

$transportProvider = new RatchetTransportProvider("127.0.0.1", 3000);

$router->addTransportProvider($transportProvider);

$router->start();
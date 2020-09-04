<?php
namespace Mafia;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

Class Mafia implements MessageComponentInterface {
    public function onOpen(ConnectionInterface $conn) {

    }

    public function onMessage(ConnectionInterface $from, $message) {

    }

    public function onClose(ConnectionInterface $conn) {

    }

    public function onError(ConnectionInterface $conn, \Exception $e) {

    }
}
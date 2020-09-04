<?php
namespace Mafia;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

Class Mafia implements MessageComponentInterface {
    protected $players;

    public function __construct() {
        $this->players = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $message) {
        echo $message;
    }

    public function onClose(ConnectionInterface $conn) {
        $this->players->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}
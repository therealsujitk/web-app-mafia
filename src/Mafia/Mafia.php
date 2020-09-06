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
        $action = substr($message, 0, 1);

        if($action == "*") {
            $townID = substr($message, 1);
            $this->players->attach($from, $townID);

            foreach ($this->players as $player) {
                if ($this->players[$player] == $townID && $player != $from) {
                    $player->send('*');
                }
            }
        }
        else if($action == "!") {
            $townID = substr($message, 1);
            $this->players->attach($from, $townID);

            foreach ($this->players as $player) {
                if ($this->players[$player] == $townID && $player != $from) {
                    $player->send('!');
                }
            }
        }
        else if($action == "%") {
            $townID = substr($message, 1);
            $this->players->attach($from, $townID);

            foreach ($this->players as $player) {
                if ($this->players[$player] == $townID && $player != $from) {
                    $player->send('%');
                }
            }
        }
        else if($action == "$") {
            $townID = substr($message, 1);
            $this->players->attach($from, $townID);

            foreach ($this->players as $player) {
                if ($this->players[$player] == $townID && $player != $from) {
                    $player->send('$');
                }
            }
        }
        else if($action == "#") {
            //Player voted
        }
        else {
            $townID = $message;
            $this->players->attach($from, $townID);
        }
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
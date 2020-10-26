<?php

require '../vendor/voryx/thruway/Examples/bootstrap.php';

use Thruway\ClientSession;
use Thruway\Connection;

include('../conn.php');
session_start();

$townID = $_SESSION["townID"];
$check = $_POST["check"];

$query = "SELECT has_started FROM town_details WHERE town_id = '$townID';";
if($check != 'true')
    die('Sorry, something went terribly wrong');
else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["has_started"] == '0')
    die('success');

$query = "ALTER TABLE town_" . $townID . " RENAME TO town_" . $townID . "_del;";
mysqli_query($conn, $query);

$query = "CREATE TABLE town_" . $townID . " AS SELECT name, avatar FROM town_" . $townID . "_del;";
mysqli_query($conn, $query);

$query = "ALTER TABLE town_" . $townID . " ADD user_id INT(2) UNSIGNED AUTO_INCREMENT PRIMARY KEY;";
mysqli_query($conn, $query);

$query = "DROP TABLE town_" . $townID . "_del;";
mysqli_query($conn, $query);

$query = "UPDATE town_details SET time_stamp = NOW() WHERE town_id = '$townID';";
mysqli_query($conn, $query);

$query = "UPDATE town_details SET has_started = 0, game_index = 0, daily_index = 0, daily_max = 2 WHERE town_id = '$townID';";
mysqli_query($conn, $query);

mysqli_close($conn);

echo 'success';

$onClose = function ($msg) {
    echo $msg;
};

$connection = new Connection(
    [
        "realm"   => 'mafia',
        "onClose" => $onClose,
        "url"     => 'ws://127.0.0.1:3000',
    ]
);

$connection->on('open',
    function (ClientSession $session) use ($townID, $connection) {
        $session->publish($townID, ['restart game'], [], ["acknowledge" => true])->then(
            function () {
                echo "Publish Acknowledged!\n";
            },
            function ($error) {
                echo "Publish Error {$error}\n";
            }
        );

        $connection->close();
    }
);

$connection->open();
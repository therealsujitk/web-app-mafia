<?php

require '../vendor/voryx/thruway/Examples/bootstrap.php';

use Thruway\ClientSession;
use Thruway\Connection;

include('../conn.php');
session_start();

$townID = $_SESSION["townID"];
$userID = $_SESSION["userID"];
$check = $_POST["check"];
$owner = false;

if($check != 'true')
	die('Sorry, something went terribly wrong');

$query = "SELECT has_started FROM town_details WHERE town_id = '$townID';";

if(mysqli_fetch_assoc(mysqli_query($conn, $query))["has_started"] == '0') {
	$query = "SELECT user_id FROM town_" . $townID . ";";
	if(mysqli_fetch_assoc(mysqli_query($conn, $query))["user_id"] == $userID)
		$owner = true;

	$query = "DELETE FROM town_" . $townID . " WHERE user_id = " . $userID . ";";
	mysqli_query($conn, $query);

	session_unset();
	session_destroy();
	echo 'success';
}
else {
	die('Sorry, something went terribly wrong.');
}

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
    function (ClientSession $session) use ($townID, $owner, $userID, $connection) {
		if($owner) {
			$session->publish($townID, ['owner left'], [], ["acknowledge" => true])->then(
				function () {
					echo "Publish Acknowledged!\n";
				},
				function ($error) {
					echo "Publish Error {$error}\n";
				}
			);	
		}
		else {
			$session->publish($townID, ['player left', $userID], [], ["acknowledge" => true])->then(
				function () {
					echo "Publish Acknowledged!\n";
				},
				function ($error) {
					echo "Publish Error {$error}\n";
				}
			);	
		}
        $connection->close();
    }
);

$connection->open();

mysqli_close($conn);
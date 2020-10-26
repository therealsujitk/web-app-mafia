<?php

require '../vendor/voryx/thruway/Examples/bootstrap.php';

use Thruway\ClientSession;
use Thruway\Connection;

session_start();
include('../conn.php');

$name = $_SESSION["name"];
$message = trim($_POST["message"]);
$message = str_replace("'", "\'", $message);
$message = str_replace("&", "&amp;", $message);
$message = str_replace("<", "&lt;", $message);
$message = str_replace(">", "&gt;", $message);

if($message === '')
	die('success');

if($_SESSION["dailyIndex"]%2 == 0) {
	$query = "SELECT user_id FROM town_" . $_SESSION["townID"] . " WHERE is_mafia = 1 AND is_killed = 0 AND is_executed = 0 AND name = '$name';";
	if(mysqli_fetch_assoc(mysqli_query($conn, $query))) {
		$query = "INSERT INTO chat_" . $_SESSION["townID"] . " (name, message) VALUES('$name', '$message');";
		mysqli_query($conn, $query);
	}
	else {
		die('Sorry, You are not allowed to send messages.');
	}
}
else {
	$query = "SELECT user_id FROM town_" . $_SESSION["townID"] . " WHERE is_killed = 0 AND is_executed = 0 AND name = '$name';";
	if(mysqli_fetch_assoc(mysqli_query($conn, $query))) {
		$query = "INSERT INTO chat_" . $_SESSION["townID"] . " (name, message) VALUES('$name', '$message');";
		mysqli_query($conn, $query);
	}
	else {
		die('Sorry, You are not allowed to send messages right now.');
	}
}

mysqli_close($conn);

echo 'success';

$townID = $_SESSION["townID"];

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
		$session->publish($townID, ['new message'], [], ["acknowledge" => true])->then(
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
<?php
session_start();
include('../conn.php');

$name = $_SESSION["name"];
$message = strip_tags($_POST["message"]);
$message = str_replace("'", "\'", $message);
$message = trim($message);

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
<?php
	session_start();
	include('conn.php');
	
	$name = $_SESSION["name"];
	$message = $_POST["message"];
	$message = str_replace("'", "\'", $message);
	$message = trim($message);
	
	if($message === '')
		die('Success!');
	
	$query = "INSERT INTO chat_" . $_SESSION["townID"] . " (name, message) VALUES('$name', '$message');";
	mysqli_query($conn, $query);
	
	mysqli_close($conn);
	
	echo 'Success!';
?>

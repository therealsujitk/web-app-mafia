<?php
	include('conn.php');
	session_start();
	
	$townID = $_SESSION["townID"];
	$userID = $_SESSION["userID"];
	$query = "SELECT has_started FROM town_details WHERE town_id = '$townID';";
	
	if(mysqli_fetch_assoc(mysqli_query($conn, $query))["has_started"] == 1) {
		$query = "UPDATE town_" . $townID . " SET is_executed = 1 WHERE user_id = " . $userID . ";";
		mysqli_query($conn, $query);
	}
	else {
		$query = "DELETE FROM town_" . $townID . " WHERE user_id = " . $userID . ";";
		mysqli_query($conn, $query);
	}
	
	session_unset();
	session_destroy();
	mysqli_close($conn);
	
	echo 'Success!';
?>
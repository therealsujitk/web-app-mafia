<?php
	session_start();
	include('conn.php');
	
	$role = $_POST["role"];
	$vote = $_POST["vote"];
	$dailyIndex = $_SESSION["dailyIndex"];
	$townID = $_SESSION["townID"];
	$userID = $_SESSION["userID"];
	
	if($role == 'mafia') {
		$night = $dailyIndex / 2;
		$query = "UPDATE town_" . $townID . " SET night_" . $night . " = 1 WHERE user_id = " . $vote . ";";
		mysqli_query($conn, $query);
	}
	else if($role == 'medic') {
		$night = $dailyIndex / 2;
		$query = "UPDATE town_" . $townID . " SET medic_" . $night . " = 1 WHERE user_id = " . $vote . ";";
		mysqli_query($conn, $query);
		$query = "UPDATE town_" . $townID . " SET saved = 1 WHERE user_id = " . $vote . ";";
		mysqli_query($conn, $query);
	}
	else if($role == 'citizen') {
		$day = $dailyIndex/2 + 0.5;
		$query = "UPDATE town_" . $townID . " SET day_" . $day . " = " . $vote . " WHERE user_id = " . $userID . ";";
		mysqli_query($conn, $query);
	}
	else {
		die('Sorry, something went terribly wrong.');
	}
	
	mysqli_close($conn);
	
	echo 'Success!';
?>

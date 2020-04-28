<?php
	include('conn.php');
	$townID = $_POST['townID'];
	$name = $_POST['name'];
	$avatar = $_POST['avatar'];
	
	$query = "SELECT COUNT(user_id) FROM town_" . $townID . ";";
	
	if(!mysqli_query($conn, $query)) {
		//Error! Town does not exist
	}
	else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["COUNT(user_id)"] == 10) {
		//Error! Town is full
	}
	else {
		$query = "INSERT INTO town_".$townID." (name, avatar) VALUES('$name', '$avatar');";
		mysqli_query($conn, $query);
	}
	
	$query = "SELECT user_id FROM town_" . $townID . " WHERE name = '$name';";
	$userID = mysqli_fetch_assoc(mysqli_query($conn, $query))["user_id"];
	
	$query = "SELECT town_name, mob_name FROM town_details WHERE town_id = '$townID';";
	$town = mysqli_fetch_assoc(mysqli_query($conn, $query))["town_name"];
	$mob = mysqli_fetch_assoc(mysqli_query($conn, $query))["mob_name"];
	
	mysqli_close($conn);
	
	session_start();
	$_SESSION["townID"] = $townID;
	$_SESSION["userID"] = $userID;
	$_SESSION["name"] = $name;
	$_SESSION["town"] = $town;
	$_SESSION["mob"] = $mob;
?>

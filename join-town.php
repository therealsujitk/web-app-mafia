<?php
	$conn = new mysqli('localhost', 'root', '299792458', 'mafia');
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
		
	mysqli_close($conn);
	
	session_start();
	$_SESSION["townID"] = $townID;
	$_SESSION["userID"] = $userID;
?>
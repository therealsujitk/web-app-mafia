<?php
	include('conn.php');
	session_start();
	$townID = $_POST['townID'];
	$name = strip_tags(substr($_POST['name'], 0, 10));
	$avatar = $_POST['avatar'];
	
	$name = str_replace("'", "\'", $name);
	$name = trim($name);
	
	$query = "SELECT COUNT(user_id) FROM town_" . $townID . ";";
	$query1 = "SELECT name FROM town_" . $townID . " WHERE name = '$name';";
	
	if($name == '') {
		die("Please enter a valid name.");
	}
	else if($townID == $_SESSION["townID"]) {
		die("You are already in this town. Click <b>Continue Playing</b> at the top right corner to get back to your game.");
	}
	else if(!mysqli_query($conn, $query)) {
		die("There are no towns having the Town ID <b>$townID</b>. Please recheck the Town ID you have entered.");
	}
	else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["COUNT(user_id)"] == 10) {
		die("Sorry, this town is already full. Please try");
	}
	else if(mysqli_fetch_assoc(mysqli_query($conn, $query1))) {
		die("Sorry, that name has already been taken in this town. Please use a different name.");
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
	
	$_SESSION["townID"] = $townID;
	$_SESSION["userID"] = $userID;
	$_SESSION["name"] = $name;
	$_SESSION["town"] = $town;
	$_SESSION["mob"] = $mob;
	$_SESSION["dailyIndex"] = '0';
	$_SESSION["message"] = '';
	
	echo 'Success!';
?>

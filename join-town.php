<?php
	include('conn.php');
	$townID = $_POST['townID'];
	$name = $_POST['name'];
	$avatar = $_POST['avatar'];
	
	$name = str_replace("'", "\'", $name);
	$name = trim($name);
	
	$query = "SELECT COUNT(user_id) FROM town_" . $townID . ";";
	$query1 = "SELECT name FROM town_" . $townID . " WHERE name = '$name';";
	
	if($name == '') {
		die("Please enter a valid name.");
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
	
	session_start();
	$_SESSION["townID"] = $townID;
	$_SESSION["userID"] = $userID;
	$_SESSION["name"] = $name;
	$_SESSION["town"] = $town;
	$_SESSION["mob"] = $mob;
	$_SESSION["dailyIndex"] = '0';
	
	$message = '<span>The citizens of <b>' . $town . '</b> are sleeping. Zzz</span>';
			
	$query = "SELECT user_id FROM town_" . $townID . " WHERE is_mafia = 1;";
	if($result = mysqli_query($conn, $query)) {
		while($row = mysqli_fetch_assoc($result)) {
			if($row["user_id"] == $userID) {
				$message = '<span>Welcome members of <b>' . $mob . '</b>, discuss below on who you would like to kill, after that click the <b>Kill</b> button to choose your first victim.</span>';
				break;
			}
		}
	}

	$query = "SELECT user_id FROM town_" . $townID . " WHERE is_medic = 1;";
	if($result = mysqli_query($conn, $query)) {
		while($row = mysqli_fetch_assoc($result)) {
			if($row["user_id"] == $userID) {
				$message = '</span>Hello there! You probably already know this but, you are the towns medic. Click the <b>Heal</b> button below to use your amazing abilities. :)</span>';
				break;
			}
		}
	}

	$query = "SELECT user_id FROM town_" . $townID . " WHERE is_sherrif = 1;";
	if($result = mysqli_query($conn, $query)) {
		while($row = mysqli_fetch_assoc($result)) {
			if($row["user_id"] == $userID) {
				$message = '<span>Hello there! You probably already know this but, you are the towns sherrif. Click the <b>Reveal</b> button below to check whether a citizen is a mafia member.</span>';
				break;
			}
		}
	}
	
	$_SESSION["message"] = $message;
	
	mysqli_close($conn);
	
	echo 'Success!';
?>

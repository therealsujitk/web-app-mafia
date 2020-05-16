<?php
	$name = $_POST['name'];
	$avatar = $_POST['avatar'];
	$town = $_POST['town'];
	$mob = $_POST['mob'];

	$name = str_replace("'", "\'", $name);
	$town = str_replace("'", "\'", $town);
	$mob = str_replace("'", "\'", $mob);
	
	$name = trim($name);
	$town = trim($town);
	$mob = trim($mob);
		
	if($name == "")
		die("Please enter a valid name.");
		
	if($town == "")
		$town = 'Crystal Cove';
	
	if($mob == "")
		$mob = 'Mystery Inc.';

	function uniqueID() {
		$arr = array('0', 'a', '1', 'b', '2','c', '3','d', '4','e', '5','f', '6','g', '7','h', '8','i', '9','j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w','x', 'y', 'z');
		
		$ID = '';
		
		for($i=0; $i<15; ++$i) {
			$ID = $ID.$arr[rand(0, 35)];
		}
			
		return $ID;
	}
	
	while(true) {
		$townID = uniqueID();
		$query = "SELECT * FROM town_details WHERE town_id = " . $townID . ";";
		if(!mysqli_query($conn, $query))
			break;
	}
	
	include('conn.php');
	
	$query = "INSERT INTO town_details (town_id, town_name, mob_name) VALUES('$townID', '$town', '$mob');";
	mysqli_query($conn, $query);
	
	$query = "CREATE TABLE town_" . $townID . " (
		user_id INT(2) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(30) NOT NULL,
		avatar VARCHAR(255) NOT NULL
	);";
	mysqli_query($conn, $query);
	
	$query = "INSERT INTO town_" . $townID . " (name, avatar) VALUES('$name', '$avatar');";
	mysqli_query($conn, $query);
	
	session_start();
	$_SESSION["townID"] = $townID;
	$_SESSION["userID"] = '1';
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

<?php
	function uniqueID() {
		$arr = array('0', 'a', '1', 'b', '2','c', '3','d', '4','e', '5','f', '6','g', '7','h', '8','i', '9','j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w','x', 'y', 'z');
		
		$ID = '';
		
		for($i=0; $i<15; ++$i) {
			$ID = $ID.$arr[rand(0, 35)];
		}
			
		return $ID;
	}
	
	include('conn.php');
	
	while(true) {
		$townID = uniqueID();
		$query = "SELECT * FROM town_details WHERE town_id = " . $townID . ";";
		if(!mysqli_query($conn, $query))
			break;
	}
	
	$town = $_POST['town'];
	$mob = $_POST['mob'];
	
	$town = str_replace("'", "\'", $town);
	$mob = str_replace("'", "\'", $mob);
	
	$query = "INSERT INTO town_details (town_id, town_name, mob_name, owner_id) VALUES('$townID', '$town', '$mob', 1);";
	mysqli_query($conn, $query);
	
	$query = "CREATE TABLE town_" . $townID . " (
		user_id INT(2) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(30) NOT NULL,
		avatar VARCHAR(255) NOT NULL
	);";
	mysqli_query($conn, $query);
	
	$name = $_POST['name'];
	$avatar = $_POST['avatar'];
	
	$name = str_replace("'", "\'", $name);
	
	$query = "INSERT INTO town_" . $townID . " (name, avatar) VALUES('$name', '$avatar');";
	mysqli_query($conn, $query);
	
	mysqli_close($conn);
	
	session_start();
	$_SESSION["townID"] = $townID;
	$_SESSION["userID"] = 1;
?>

<?php

$name = substr(trim($_POST['name']), 0, 10);
$avatar = $_POST['avatar'];
$town = substr(trim($_POST['town']), 0, 20);
$mob = substr(trim($_POST['mob']), 0, 20);

$name = str_replace("'", "\'", $name);
$name = str_replace("&", "&amp;", $name);
$name = str_replace("<", "&lt;", $name);
$name = str_replace(">", "&gt;", $name);

$town = str_replace("'", "\'", $town);
$town = str_replace("&", "&amp;", $town);
$town = str_replace("<", "&lt;", $town);
$town = str_replace(">", "&gt;", $town);

$mob = str_replace("'", "\'", $mob);
$mob = str_replace("&", "&amp;", $mob);
$mob = str_replace("<", "&lt;", $mob);
$mob = str_replace(">", "&gt;", $mob);
	
if($name == "")
	die("Please enter a valid name.");
	
if($town == "") {
	$townSet = array('Crystal Cove', 'Riverdale', 'Smallville', 'Storybrooke', 'Greendale', 'Gatorsburg', 'Coolsville');
	$town = $townSet[rand(0, 6)];
}

if($mob == "") {
	$mobSet = array('The Gargoyles', 'The Ghoulies', 'The Russian Syndicate', 'The Irish Mob', 'The Bratva');
	$mob = $mobSet[rand(0, 4)];
}

function uniqueID() {
	$arr = array('0', 'a', '1', 'b', '2','c', '3','d', '4','e', '5','f', '6','g', '7','h', '8','i', '9','j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w','x', 'y', 'z');
	
	$ID = '';
	
	for($i=0; $i<7; ++$i) {
		$ID = $ID.$arr[rand(0, 35)];
	}
		
	return $ID;
}

include('../conn.php');

while(true) {
	$townID = uniqueID();
	$query = "SELECT town_name FROM town_details WHERE town_id = " . $townID . ";";
	if(!mysqli_query($conn, $query))
		break;
}

$query = "INSERT INTO town_details (town_id, time_stamp, town_name, mob_name) VALUES('$townID', NOW(), '$town', '$mob');";
mysqli_query($conn, $query);

$query = "CREATE TABLE town_" . $townID . " (
	user_id INT(2) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(30) NOT NULL,
	avatar VARCHAR(255) NOT NULL
);";
mysqli_query($conn, $query);

$query = "INSERT INTO town_" . $townID . " (name, avatar) VALUES('$name', '$avatar');";
mysqli_query($conn, $query);

$query = "SELECT town_id FROM town_details WHERE time_stamp < (NOW() - INTERVAL 1 HOUR) AND has_started = 0";
if($result = mysqli_query($conn, $query)) {
	while($row = mysqli_fetch_assoc($result)) {
		$tempID = $row["town_id"];
		$query = "DROP TABLE town_" . $tempID . ";";
		mysqli_query($conn, $query);
		$query = "DROP TABLE chat_" . $tempID . ";";
		mysqli_query($conn, $query);
		$query = "DELETE FROM town_details WHERE town_id = '$tempID';";
		mysqli_query($conn, $query);
	}
}

$query = "SELECT town_id FROM town_details WHERE time_stamp < (NOW() - INTERVAL 3 HOUR) AND has_started = 1";
if($result = mysqli_query($conn, $query)) {
	while($row = mysqli_fetch_assoc($result)) {
		$tempID = $row["town_id"];
		$query = "DROP TABLE town_" . $tempID . ";";
		mysqli_query($conn, $query);
		$query = "DROP TABLE chat_" . $tempID . ";";
		mysqli_query($conn, $query);
		$query = "DELETE FROM town_details WHERE town_id = '$tempID';";
		mysqli_query($conn, $query);
	}
}

mysqli_close($conn);

session_start();
$_SESSION["townID"] = $townID;
$_SESSION["name"] = $name;
$_SESSION["town"] = $town;
$_SESSION["mob"] = $mob;
$_SESSION["dailyIndex"] = '0';
$_SESSION["message"] = '';
$_SESSION["revealed"] = '';

echo 'success' . $townID;
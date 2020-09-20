<?php

require '../vendor/voryx/thruway/Examples/bootstrap.php';

use Thruway\ClientSession;
use Thruway\Connection;

session_start();
include('../conn.php');
$townID = 	$_SESSION["townID"];
$query = "SELECT COUNT(user_id) FROM town_" . $townID . ";";
$population = mysqli_fetch_assoc(mysqli_query($conn, $query))["COUNT(user_id)"];

if($population < 6)
	die('Sorry, you need at least <b>six</b> players to start the game.');

$query = "SELECT has_started FROM town_details WHERE town_id = '$townID';";
if(mysqli_fetch_assoc(mysqli_query($conn, $query))["has_started"] != '0')
	die('Sorry, something went terribly wrong.');

$query = "UPDATE town_details SET has_started = 2 WHERE town_id = '$townID';";
mysqli_query($conn, $query);

$query = "ALTER TABLE town_" . $townID . " ADD is_mafia INT(1) NOT NULL DEFAULT 0;";
mysqli_query($conn, $query);

$query = "ALTER TABLE town_" . $townID . " ADD is_poser INT(1) NOT NULL DEFAULT 0;";
mysqli_query($conn, $query);

$query = "ALTER TABLE town_" . $townID . " ADD is_medic INT(1) NOT NULL DEFAULT 0;";
mysqli_query($conn, $query);

$query = "ALTER TABLE town_" . $townID . " ADD is_sherrif INT(1) NOT NULL DEFAULT 0;";
mysqli_query($conn, $query);

$query = "ALTER TABLE town_" . $townID . " ADD is_killed INT(1) NOT NULL DEFAULT 0;";
mysqli_query($conn, $query);

$query = "ALTER TABLE town_" . $townID . " ADD is_executed INT(1) NOT NULL DEFAULT 0;";
mysqli_query($conn, $query);

if($population == 6) {
	$query = "SELECT * FROM town_" . $townID . " ORDER BY RAND();";
	$result = mysqli_query($conn, $query);
	$i = 0;

	while($row = mysqli_fetch_assoc($result)) {
		if($i < 1) {
			$userID = $row["user_id"];
			$query = "UPDATE town_" . $townID . " SET is_mafia = 1 WHERE user_id = " . $userID . ";";
			mysqli_query($conn, $query);
			++$i;
		}
		else if($i < 2) {
			$userID = $row["user_id"];
			$query = "UPDATE town_" . $townID . " SET is_medic = 1 WHERE user_id = " . $userID . ";";
			mysqli_query($conn, $query);
			++$i;
		}
		else if($i < 3) {
			$userID = $row["user_id"];
			$query = "UPDATE town_" . $townID . " SET is_sherrif = 1 WHERE user_id = " . $userID . ";";
			mysqli_query($conn, $query);
			++$i;
		}
		else
			break;
	}
}
else if($population == 7) {
	$query = "SELECT * FROM town_" . $townID . " ORDER BY RAND();";
	$result = mysqli_query($conn, $query);
	$i = 0;

	while($row = mysqli_fetch_assoc($result)) {
		if($i < 1) {
			$userID = $row["user_id"];
			$query = "UPDATE town_" . $townID . " SET is_mafia = 1 WHERE user_id = " . $userID . ";";
			mysqli_query($conn, $query);
			++$i;
		}
		else if($i < 2) {
			$userID = $row["user_id"];
			$query = "UPDATE town_" . $townID . " SET is_medic = 1 WHERE user_id = " . $userID . ";";
			mysqli_query($conn, $query);
			++$i;
		}
		else if($i < 3) {
			$userID = $row["user_id"];
			$query = "UPDATE town_" . $townID . " SET is_sherrif = 1 WHERE user_id = " . $userID . ";";
			mysqli_query($conn, $query);
			++$i;
		}
		else
			break;
	}
}
else if($population == 8) {
	$query = "SELECT * FROM town_" . $townID . " ORDER BY RAND();";
	$result = mysqli_query($conn, $query);
	$i = 0;

	while($row = mysqli_fetch_assoc($result)) {
		if($i < 2) {
			$userID = $row["user_id"];
			$query = "UPDATE town_" . $townID . " SET is_mafia = 1 WHERE user_id = " . $userID . ";";
			mysqli_query($conn, $query);
			++$i;
		}
		else if($i < 3) {
			$userID = $row["user_id"];
			$query = "UPDATE town_" . $townID . " SET is_medic = 1 WHERE user_id = " . $userID . ";";
			mysqli_query($conn, $query);
			++$i;
		}
		else if($i < 4) {
			$userID = $row["user_id"];
			$query = "UPDATE town_" . $townID . " SET is_sherrif = 1 WHERE user_id = " . $userID . ";";
			mysqli_query($conn, $query);
			++$i;
		}
		else
			break;
	}
}
else if($population == 9) {
	$query = "SELECT * FROM town_" . $townID . " ORDER BY RAND();";
	$result = mysqli_query($conn, $query);
	$i = 0;

	while($row = mysqli_fetch_assoc($result)) {
		if($i < 2) {
			$userID = $row["user_id"];
			$query = "UPDATE town_" . $townID . " SET is_mafia = 1 WHERE user_id = " . $userID . ";";
			mysqli_query($conn, $query);
			++$i;
		}
		else if($i < 3) {
			$userID = $row["user_id"];
			$query = "UPDATE town_" . $townID . " SET is_medic = 1 WHERE user_id = " . $userID . ";";
			mysqli_query($conn, $query);
			++$i;
		}
		else if($i < 4) {
			$userID = $row["user_id"];
			$query = "UPDATE town_" . $townID . " SET is_sherrif = 1 WHERE user_id = " . $userID . ";";
			mysqli_query($conn, $query);
			++$i;
		}
		else if($i < 5) {
			$userID = $row["user_id"];
			$query = "UPDATE town_" . $townID . " SET is_poser = 1 WHERE user_id = " . $userID . ";";
			mysqli_query($conn, $query);
			++$i;
		}
		else
			break;
	}
}
else if($population == 10) {
	$query = "SELECT * FROM town_" . $townID . " ORDER BY RAND();";
	$result = mysqli_query($conn, $query);
	$i = 0;

	while($row = mysqli_fetch_assoc($result)) {
		if($i < 3) {
			$userID = $row["user_id"];
			$query = "UPDATE town_" . $townID . " SET is_mafia = 1 WHERE user_id = " . $userID . ";";
			mysqli_query($conn, $query);
			++$i;
		}
		else if($i < 4) {
			$userID = $row["user_id"];
			$query = "UPDATE town_" . $townID . " SET is_medic = 1 WHERE user_id = " . $userID . ";";
			mysqli_query($conn, $query);
			++$i;
		}
		else if($i < 5) {
			$userID = $row["user_id"];
			$query = "UPDATE town_" . $townID . " SET is_sherrif = 1 WHERE user_id = " . $userID . ";";
			mysqli_query($conn, $query);
			++$i;
		}
		else if($i < 6) {
			$userID = $row["user_id"];
			$query = "UPDATE town_" . $townID . " SET is_poser = 1 WHERE user_id = " . $userID . ";";
			mysqli_query($conn, $query);
			++$i;
		}
		else
			break;
	}
}
else if($population > 10) {
	die('Sorry, you have exceeded the maximum number of players. We do not accept more than <b>ten</b> players.');
}

$query = "ALTER TABLE town_" . $townID . " ADD night_0 INT(1) NOT NULL DEFAULT 0;";
mysqli_query($conn, $query);

$query = "ALTER TABLE town_" . $townID . " ADD medic_0 INT(1) NOT NULL DEFAULT 0;";
mysqli_query($conn, $query);

$query = "ALTER TABLE town_" . $townID . " ADD day_1 INT(2) NOT NULL DEFAULT 0;";
mysqli_query($conn, $query);

$query = "CREATE TABLE chat_" . $townID . " (
	name VARCHAR(15) NOT NULL,
	message VARCHAR(2000) NOT NULL
);";
mysqli_query($conn, $query);

$query = "CREATE TRIGGER game_trigger_" . $townID . " BEFORE UPDATE ON town_" . $townID . " FOR EACH ROW UPDATE town_details SET game_index = game_index + 1 WHERE town_id = '$townID';";
mysqli_query($conn, $query);

$query = "CREATE TRIGGER daily_trigger_" . $townID . " AFTER UPDATE ON town_" . $townID . " FOR EACH ROW UPDATE town_details SET daily_index = daily_index + 1 WHERE town_id = '$townID' AND game_index >= daily_max;";
mysqli_query($conn, $query);

$query = "UPDATE statistics SET players_joined = players_joined + " . $population . " WHERE id = 1;";
mysqli_query($conn, $query);

$query = "UPDATE statistics SET games_played = games_played + 1 WHERE id = 1;";
mysqli_query($conn, $query);

$query = "UPDATE town_details SET has_started = 1 WHERE town_id = '$townID';";
mysqli_query($conn, $query);

mysqli_close($conn);

echo 'success';

$onClose = function ($msg) {
    echo $msg;
};

$connection = new Connection(
    [
        "realm"   => 'mafia',
        "onClose" => $onClose,
        "url"     => 'ws://127.0.0.1:3000',
    ]
);

$connection->on('open',
    function (ClientSession $session) use ($townID, $connection) {
		$session->publish($townID, ['start game'], [], ["acknowledge" => true])->then(
			function () {
				echo "Publish Acknowledged!\n";
			},
			function ($error) {
				echo "Publish Error {$error}\n";
			}
		);	

		$connection->close();
		}
);

$connection->open();
<?php

require '../vendor/voryx/thruway/Examples/bootstrap.php';

use Thruway\ClientSession;
use Thruway\Connection;

session_start();
include('../conn.php');

$role = $_POST["role"];
$vote = $_POST["vote"];
$dailyIndex = $_SESSION["dailyIndex"];
$townID = $_SESSION["townID"];
$userID = $_SESSION["userID"];

$query = "SELECT is_mafia, is_medic, is_sherrif FROM town_" . $_SESSION["townID"] . " WHERE user_id = " . $userID . ";";

if($role == 'mafia' && mysqli_fetch_assoc(mysqli_query($conn, $query))["is_mafia"]) {
	$night = $dailyIndex / 2;
	$query = "SELECT user_id FROM town_" . $townID . " WHERE night_" . $night . " <> 0;";
	if(!mysqli_fetch_assoc(mysqli_query($conn, $query))) {
		$query = "UPDATE town_" . $townID . " SET night_" . $night . " = 1 WHERE user_id = " . $vote . " AND night_" . $night . " = 0;";
		mysqli_query($conn, $query);
	}
}
else if($role == 'medic' && mysqli_fetch_assoc(mysqli_query($conn, $query))["is_medic"]) {
	$night = $dailyIndex / 2;
	$query = "SELECT user_id FROM town_" . $townID . " WHERE medic_" . $night . " <> 0;";
	if(!mysqli_fetch_assoc(mysqli_query($conn, $query))) {
		$query = "UPDATE town_" . $townID . " SET medic_" . $night . " = 1 WHERE user_id = " . $vote . " AND medic_" . $night . " = 0;";
		mysqli_query($conn, $query);
	}
}
else if($role == 'sheriff' && mysqli_fetch_assoc(mysqli_query($conn, $query))["is_sherrif"]) {
	$query = "SELECT name, avatar, is_mafia FROM town_" . $_SESSION["townID"] . " WHERE user_id = " . $vote . ";";

	$name = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
	$avatar = mysqli_fetch_assoc(mysqli_query($conn, $query))["avatar"];
	$mob = $_SESSION["mob"];

	if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_mafia"])
		$_SESSION["revealed"] = '<table cellpadding="0" cellspacing="0" style="width: 100%;">
			<td class="header2" style="text-align: left;">Citizens Role</td>
			<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
		</table>
		<table cellpadding="0" cellspacing="0" style="width: 100%;">
			<td><img src="' . $avatar . '" style="height: 150px;"></img></td>
			<td><h3>Mafia</h3><br><p style="padding: 0; margin: 0;">You hit the jackpot! <b>' . $name . '</b> is a member of <b>' . $mob . '</b>!!</p></td>
		</table>';
	else
		$_SESSION["revealed"] = '<table cellpadding="0" cellspacing="0" style="width: 100%;">
			<td class="header2" style="text-align: left;">Citizens Role</td>
			<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
		</table>
		<table cellpadding="0" cellspacing="0" style="width: 100%;">
			<td><img src="' . $avatar . '" style="height: 150px;"></img></td>
			<td><h3>Not Mafia</h3><br><p style="padding: 0; margin: 0;">Sorry, turns out <b>' . $name . '</b> is not a member of <b>' . $mob . '</b>.</p></td>
		</table>';
}
else if($role == 'citizen') {
	$day = $dailyIndex/2 + 0.5;
	$query = "UPDATE town_" . $townID . " SET day_" . $day . " = " . $vote . " WHERE user_id = " . $userID . " AND day_" . $day . " = 0;";
	mysqli_query($conn, $query);
}
else if($role == 'timeup') {
	if($dailyIndex %2 == 0) {
		$query = "SELECT time_stamp FROM town_details WHERE town_id = '$townID' AND time_stamp <= (NOW() - INTERVAL 2.5 MINUTE);";
		if(mysqli_query($conn, $query)) {
			$night = $dailyIndex / 2;

			$query = "SELECT user_id FROM town_" . $townID . " WHERE night_" . $night . " <> 0;";

			if(!mysqli_fetch_assoc(mysqli_query($conn, $query))) {
				$query = "SELECT user_id FROM town_" . $townID . " WHERE is_mafia = 0 ORDER BY RAND();";
				$vote = mysqli_fetch_assoc(mysqli_query($conn, $query))["user_id"];
				$query = "UPDATE town_" . $townID . " SET night_" . $night . " = 1 WHERE user_id = " . $vote . " AND night_" . $night . " = 0;";
				mysqli_query($conn, $query);
			}

			$query =  "SELECT user_id FROM town_" . $townID . " WHERE is_medic = 1 is_killed = 0 AND is_executed = 0;";
			if(mysqli_fetch_assoc(mysqli_query($conn, $query))) {
				$query = "SELECT user_id FROM town_" . $townID . " WHERE medic_" . $night . " <> 0;";
				if(!mysqli_fetch_assoc(mysqli_query($conn, $query))) {
					$prev = $night - 1;
					if($prev < 0)
						$prev = 0;
					
					$query = "SELECT user_id FROM town_" . $townID . " WHERE night_" . $prev . " = 0 ORDER BY RAND();";
					$vote = mysqli_fetch_assoc(mysqli_query($conn, $query))["user_id"];
					$query = "UPDATE town_" . $townID . " SET medic_" . $night . " = 1 WHERE user_id = " . $vote . " AND medic_" . $night . " = 0;";
					mysqli_query($conn, $query);
				}
			}
		}
	}
	else {
		$query = "SELECT time_stamp FROM town_details WHERE town_id = '$townID' AND time_stamp <= (NOW() - INTERVAL 1 MINUTE);";
		if(mysqli_query($conn, $query)) {
			$day = $dailyIndex/2 + 0.5;

			$query = "SELECT user_id FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0 and day_" . $day . " = 0;";
			if($result = mysqli_query($conn, $query)) {
				while($row = mysqli_fetch_assoc($result)) {
					$query = "SELECT user_id FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0 ORDER BY RAND();";
					$vote = mysqli_fetch_assoc(mysqli_query($conn, $query))["user_id"];

					$query = "UPDATE town_" . $townID . " SET day_" . $day . " = " . $vote . " WHERE user_id = " . $row["user_id"] . " AND day_" . $day . " = 0;";
					mysqli_query($conn, $query);
				}
			}
		}
	}
}
else {
	die('Sorry, something went terribly wrong.');
}

$query = "SELECT daily_index, game_index, daily_max FROM town_details WHERE town_id = '$townID';";
$result = mysqli_fetch_assoc(mysqli_query($conn, $query));
$tempIndex = $result["daily_index"];

$nextSession = '0';

if($_SESSION["dailyIndex"] != $tempIndex) {
	$nextSession = '1';

	if($tempIndex%2 == 0) {
		$gameIndex = $result["game_index"];
		$dailyMax = $result["daily_max"];
		
		if($gameIndex >= $dailyMax) {
			$prev = $tempIndex/2;
			$night = $tempIndex/2;
			
			$query = "TRUNCATE TABLE chat_" . $townID . ";";
			mysqli_query($conn, $query);
		
			$query = "UPDATE town_details SET daily_max = 99 WHERE town_id = '$townID';";
			mysqli_query($conn, $query);
		
			$query = "SELECT COUNT(name) FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0;";
			$dayVoteMajority = mysqli_fetch_assoc(mysqli_query($conn, $query))["COUNT(name)"] / 2;
		
			$query = "SELECT COUNT(day_" . $prev . "), day_" . $prev . " FROM town_" . $townID . " WHERE day_" . $prev . " <> 0 GROUP BY day_" . $prev . " ORDER BY COUNT(day_" . $prev . ") DESC;";
			$row = mysqli_fetch_assoc(mysqli_query($conn, $query));
			if($row["COUNT(day_" . $prev . ")"] > $dayVoteMajority) {
				$query = "SELECT name FROM town_" . $townID . " WHERE user_id = " . $row["day_" . $prev] . ";";
				$executed = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
				$query = "UPDATE town_" . $townID . " SET is_executed = 1 WHERE name = '$executed';";
				mysqli_query($conn, $query);
			}
	
			$query = "ALTER TABLE town_" . $townID . " ADD night_" . $night . " INT(1) NOT NULL DEFAULT 0;";
			mysqli_query($conn, $query);

			$query = "ALTER TABLE town_" . $townID . " ADD medic_" . $night . " INT(1) NOT NULL DEFAULT 0;";
			mysqli_query($conn, $query);

			$next = $night + 1;
			$query = "ALTER TABLE town_" . $townID . " ADD day_" . $next . " INT(2) NOT NULL DEFAULT 0;";
			mysqli_query($conn, $query);
	
			$query = "UPDATE town_details SET game_index = 0 WHERE town_id = '$townID';";
			mysqli_query($conn, $query);
	
			$query = "SELECT COUNT(name) FROM town_" . $townID . " WHERE is_medic = 1 AND is_killed = 0 AND is_executed = 0;";
			if(mysqli_fetch_assoc(mysqli_query($conn, $query))["COUNT(name)"])
				$max = 2;
			else
				$max = 1;
			$query = "UPDATE town_details SET daily_max = " . $max . " WHERE town_id = '$townID';";
			mysqli_query($conn, $query);
		}
	}
	else {
		$gameIndex = $result["game_index"];
		$dailyMax = $result["daily_max"];
		
		if($gameIndex >= $dailyMax) {
			$prev = $tempIndex/2 - 0.5;
			
			$query = "TRUNCATE TABLE chat_" . $townID . ";";
			mysqli_query($conn, $query);
		
			$query = "UPDATE town_details SET daily_max = 99 WHERE town_id = '$townID';";
			mysqli_query($conn, $query);
		
			$query = "SELECT name FROM town_" . $townID . " WHERE night_" . $prev . " <> 0;";
			$killed = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
			$query = "SELECT name FROM town_" . $townID . " WHERE medic_" . $prev . " <> 0;";
			$healed = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
	
			if($killed != $healed) {
				$query = "UPDATE town_" . $townID . " SET is_killed = 1 WHERE name = '$killed' AND is_killed = 0;";
				mysqli_query($conn, $query);
			}
		
			$query = "UPDATE town_details SET game_index = 0 WHERE town_id = '$townID';";
			mysqli_query($conn, $query);
		
			$query = "SELECT COUNT(name) FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0;";
			$max = mysqli_fetch_assoc(mysqli_query($conn, $query))["COUNT(name)"];
			$query = "UPDATE town_details SET daily_max = " . $max . " WHERE town_id = '$townID';";
			mysqli_query($conn, $query);
		}
	}

	$query = "UPDATE town_details SET time_stamp = NOW() WHERE town_id = '$townID';";
	mysqli_query($conn, $query);
}

mysqli_close($conn);

echo 'success';

if($nextSession == '1') {
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
			$session->publish($townID, ['update index'], [], ["acknowledge" => true])->then(
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
}
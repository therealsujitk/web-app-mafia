<?php
	session_start();
	include('conn.php');
	
	$role = $_POST["role"];
	$vote = $_POST["vote"];
	$dailyIndex = $_SESSION["dailyIndex"];
	$townID = $_SESSION["townID"];
	$userID = $_SESSION["userID"];
	
	$query = "SELECT is_mafia, is_medic, is_sherrif FROM town_" . $_SESSION["townID"] . " WHERE user_id = " . $userID . ";";
	
	if($role == 'mafia' && mysqli_fetch_assoc(mysqli_query($conn, $query))["is_mafia"]) {
		$night = $dailyIndex / 2;
		$query = "UPDATE town_" . $townID . " SET night_" . $night . " = 1 WHERE user_id = " . $vote . " AND night_" . $night . " = 0;";
		mysqli_query($conn, $query);
	}
	else if($role == 'medic' && mysqli_fetch_assoc(mysqli_query($conn, $query))["is_medic"]) {
		$night = $dailyIndex / 2;
		$query = "UPDATE town_" . $townID . " SET medic_" . $night . " = 1 WHERE user_id = " . $vote . " AND medic_" . $night . " = 0;";
		mysqli_query($conn, $query);
		$query = "UPDATE town_" . $townID . " SET saved = 1 WHERE user_id = " . $vote . ";";
		mysqli_query($conn, $query);
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
	else {
		die('Sorry, something went terribly wrong.');
	}
	
	mysqli_close($conn);
	
	echo 'Success!';
?>

<?php
	session_start();
	include('conn.php');
	$query = "SELECT COUNT(user_id) FROM town_" . $_SESSION["townID"] . ";";
	$population = mysqli_fetch_assoc(mysqli_query($conn, $query))["COUNT(user_id)"];
	
	$query = "ALTER TABLE town_" . $_SESSION["townID"] . " ADD is_mafia INT(1) NOT NULL DEFAULT 0;";
	mysqli_query($conn, $query);
	
	$query = "ALTER TABLE town_" . $_SESSION["townID"] . " ADD is_poser INT(1) NOT NULL DEFAULT 0;";
	mysqli_query($conn, $query);
	
	$query = "ALTER TABLE town_" . $_SESSION["townID"] . " ADD is_medic INT(1) NOT NULL DEFAULT 0;";
	mysqli_query($conn, $query);
	
	$query = "ALTER TABLE town_" . $_SESSION["townID"] . " ADD is_sherrif INT(1) NOT NULL DEFAULT 0;";
	mysqli_query($conn, $query);
	
	$query = "ALTER TABLE town_" . $_SESSION["townID"] . " ADD is_killed INT(1) NOT NULL DEFAULT 0;";
	mysqli_query($conn, $query);
	
	$query = "ALTER TABLE town_" . $_SESSION["townID"] . " ADD is_executed INT(1) NOT NULL DEFAULT 0;";
	mysqli_query($conn, $query);
	
	if($population == 6) {
		$query = "SELECT * FROM town_" . $_SESSION["townID"] . " ORDER BY RAND();";
		$result = mysqli_query($conn, $query);
		$i = 0;
	
		while($row = mysqli_fetch_assoc($result)) {
			if($i < 2) {
				$userID = $row["user_id"];
				$query = "UPDATE town_" . $_SESSION["townID"] . " SET is_mafia = 1 WHERE user_id = " . $userID . ";";
				mysqli_query($conn, $query);
				++$i;
			}
			else if($i < 3) {
				$userID = $row["user_id"];
				$query = "UPDATE town_" . $_SESSION["townID"] . " SET is_medic = 1 WHERE user_id = " . $userID . ";";
				mysqli_query($conn, $query);
				++$i;
			}
			else if($i < 4) {
				$userID = $row["user_id"];
				$query = "UPDATE town_" . $_SESSION["townID"] . " SET is_sherrif = 1 WHERE user_id = " . $userID . ";";
				mysqli_query($conn, $query);
				++$i;
			}
			else
				break;
		}
	}
	else if($population == 7) {
		$query = "SELECT * FROM town_" . $_SESSION["townID"] . " ORDER BY RAND();";
		$result = mysqli_query($conn, $query);
		$i = 0;
	
		while($row = mysqli_fetch_assoc($result)) {
			if($i < 2) {
				$userID = $row["user_id"];
				$query = "UPDATE town_" . $_SESSION["townID"] . " SET is_mafia = 1 WHERE user_id = " . $userID . ";";
				mysqli_query($conn, $query);
				++$i;
			}
			else if($i < 3) {
				$userID = $row["user_id"];
				$query = "UPDATE town_" . $_SESSION["townID"] . " SET is_medic = 1 WHERE user_id = " . $userID . ";";
				mysqli_query($conn, $query);
				++$i;
			}
			else if($i < 4) {
				$userID = $row["user_id"];
				$query = "UPDATE town_" . $_SESSION["townID"] . " SET is_sherrif = 1 WHERE user_id = " . $userID . ";";
				mysqli_query($conn, $query);
				++$i;
			}
			else
				break;
		}
	}
	else if($population == 8) {
		$query = "SELECT * FROM town_" . $_SESSION["townID"] . " ORDER BY RAND();";
		$result = mysqli_query($conn, $query);
		$i = 0;
	
		while($row = mysqli_fetch_assoc($result)) {
			if($i < 2) {
				$userID = $row["user_id"];
				$query = "UPDATE town_" . $_SESSION["townID"] . " SET is_mafia = 1 WHERE user_id = " . $userID . ";";
				mysqli_query($conn, $query);
				++$i;
			}
			else if($i < 3) {
				$userID = $row["user_id"];
				$query = "UPDATE town_" . $_SESSION["townID"] . " SET is_medic = 1 WHERE user_id = " . $userID . ";";
				mysqli_query($conn, $query);
				++$i;
			}
			else if($i < 4) {
				$userID = $row["user_id"];
				$query = "UPDATE town_" . $_SESSION["townID"] . " SET is_sherrif = 1 WHERE user_id = " . $userID . ";";
				mysqli_query($conn, $query);
				++$i;
			}
			else
				break;
		}
	}
	else if($population == 9) {
		$query = "SELECT * FROM town_" . $_SESSION["townID"] . " ORDER BY RAND();";
		$result = mysqli_query($conn, $query);
		$i = 0;
	
		while($row = mysqli_fetch_assoc($result)) {
			if($i < 3) {
				$userID = $row["user_id"];
				$query = "UPDATE town_" . $_SESSION["townID"] . " SET is_mafia = 1 WHERE user_id = " . $userID . ";";
				mysqli_query($conn, $query);
				++$i;
			}
			else if($i < 4) {
				$userID = $row["user_id"];
				$query = "UPDATE town_" . $_SESSION["townID"] . " SET is_medic = 1 WHERE user_id = " . $userID . ";";
				mysqli_query($conn, $query);
				++$i;
			}
			else if($i < 5) {
				$userID = $row["user_id"];
				$query = "UPDATE town_" . $_SESSION["townID"] . " SET is_sherrif = 1 WHERE user_id = " . $userID . ";";
				mysqli_query($conn, $query);
				++$i;
			}
			else if($i < 6) {
				$userID = $row["user_id"];
				$query = "UPDATE town_" . $_SESSION["townID"] . " SET is_poser = 1 WHERE user_id = " . $userID . ";";
				mysqli_query($conn, $query);
				++$i;
			}
			else
				break;
		}
	}
	else if($population == 10) {
		$query = "SELECT * FROM town_" . $_SESSION["townID"] . " ORDER BY RAND();";
		$result = mysqli_query($conn, $query);
		$i = 0;
	
		while($row = mysqli_fetch_assoc($result)) {
			if($i < 3) {
				$userID = $row["user_id"];
				$query = "UPDATE town_" . $_SESSION["townID"] . " SET is_mafia = 1 WHERE user_id = " . $userID . ";";
				mysqli_query($conn, $query);
				++$i;
			}
			else if($i < 4) {
				$userID = $row["user_id"];
				$query = "UPDATE town_" . $_SESSION["townID"] . " SET is_medic = 1 WHERE user_id = " . $userID . ";";
				mysqli_query($conn, $query);
				++$i;
			}
			else if($i < 5) {
				$userID = $row["user_id"];
				$query = "UPDATE town_" . $_SESSION["townID"] . " SET is_sherrif = 1 WHERE user_id = " . $userID . ";";
				mysqli_query($conn, $query);
				++$i;
			}
			else if($i < 6) {
				$userID = $row["user_id"];
				$query = "UPDATE town_" . $_SESSION["townID"] . " SET is_poser = 1 WHERE user_id = " . $userID . ";";
				mysqli_query($conn, $query);
				++$i;
			}
			else
				break;
		}
	}
	
	$query = "ALTER TABLE town_" . $_SESSION["townID"] ." ADD night_0 INT(1) NOT NULL DEFAULT 0;";
	mysqli_query($conn, $query);
	
	mysqli_close($conn);
?>

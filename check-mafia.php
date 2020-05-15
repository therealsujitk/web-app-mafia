<?php
	session_start();
	include('conn.php');
	
	$selected = $_POST["selected"];
	
	if($selected == '')
		die('Sorry, something went terribly wrong.');
	
	$query = "SELECT name, avatar, is_mafia, is_poser, is_medic, is_sherrif FROM town_" . $_SESSION["townID"] . " WHERE user_id = " . $selected . ";";
	
	$name = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
	$avatar = mysqli_fetch_assoc(mysqli_query($conn, $query))["avatar"];
	
	if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_mafia"])
		echo '<table cellpadding="0" cellspacing="0" style="width: 100%;">
			<td class="header2" style="text-align: left;">Citizens Role</td>
			<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
		</table>
		<table cellpadding="0" cellspacing="0" style="width: 100%;">
			<td><img src="' . $avatar . '" style="height: 150px;"></img></td>
			<td><h3>Mafia</h3><br><p style="padding: 0; margin: 0;">You hit the jackpot! <b>' . $name . '</b> is a mafia member!!</p></td>
		</table>';
	else
		echo '<table cellpadding="0" cellspacing="0" style="width: 100%;">
			<td class="header2" style="text-align: left;">Citizens Role</td>
			<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
		</table>
		<table cellpadding="0" cellspacing="0" style="width: 100%;">
			<td><img src="' . $avatar . '" style="height: 150px;"></img></td>
			<td><h3>Not Mafia</h3><br><p style="padding: 0; margin: 0;">Sorry, turns out <b>' . $name . '</b> is not a mafia member.</p></td>
		</table>';
	
	mysqli_close($conn);
?>

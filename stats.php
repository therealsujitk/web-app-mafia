<!DOCTYPE html public>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="/assets/css/main.css">
		<link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
		<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
		<meta content="utf-8" http-equiv="encoding">
		<script src="/assets/js/main.js"></script>
		<link rel="apple-touch-icon" sizes="180x180" href="/assets/favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/assets/favicon/favicon-16x16.png">
		<link rel="manifest" href="/assets/favicon/site.webmanifest">
		<link rel="mask-icon" href="/assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="theme-color" content="#1f1414">
		<meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="user-scalable=no">
		<title>Statistics - Mafia</title>
	</head>
	<body>
		<?php
			include('conn.php');
		?>
		<div style="position: absolute; text-align: center; width: 40%; margin-left: 50%; margin-top: 50vh; transform: translate(-50%, -50%);">
			<h1 style="font-size: 50px;">Mafia</h1>
			<table style="text-align: center; width: 100%;">
				<tr>
					<td style="font-weight: bold; font-size: 100px; color: #fff;">
						<?php
							$query = "SELECT players_joined FROM statistics WHERE id = 1;";
							echo mysqli_fetch_assoc(mysqli_query($conn, $query))["players_joined"];
						?>
					</td>
					<td style="font-weight: bold; font-size: 100px; color: #fff;">
						<?php
							$query = "SELECT games_played FROM statistics WHERE id = 1;";
							echo mysqli_fetch_assoc(mysqli_query($conn, $query))["games_played"];
							
							mysqli_close($conn);
						?>
					</td>
				</tr>
				<tr>
					<td style="font-weight: bold; font-size: 15px; color: #936c6c;">PLAYERS JOINED</td>
					<td style="font-weight: bold; font-size: 15px; color: #936c6c;">GAMES PLAYED</td>
				</tr>
			</table>
		</div>
	</body>
</html>

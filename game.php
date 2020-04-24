<!DOCTYPE html public>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="./assets/main.css">
		<link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
		<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
		<meta content="utf-8" http-equiv="encoding">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
		<script src="http://code.jquery.com/jquery-3.5.0.js"></script>
		<script src="./assets/main.js"></script>
		<script>
			var townID = <?php session_start(); echo json_encode($_SESSION["townID"]); ?>;
		</script>
		<title>Mafia</title>
	</head>
	<body>
		<div id="header">
			<table cellpadding="0" cellspacing="0" style="width: 100%; padding-left: 1%; padding-right: 1%;">
				<td><h1 style="color: white; margin: 0;">Mafia</h1></td>
				<td style="text-align: right;">
					<nav>
						<input class="header link" style="padding-left: 10px; padding-right: 10px;" type="button" value="Privacy Policy" onclick="openPrivacy()"></input>
						<input class="header link" style="padding-left: 10px; padding-right: 10px;" type="button" value="Report Bug" onclick="openBug()"></input>
						<input class="header link" style="padding-left: 10px; padding-right: 10px;" type="button" value="About Us" onclick="openAbout()"></input>
					</nav>
				</td>
			</table>
		</div>
		
		<div id="game">
			<table id="game-content">
				<tr>
					<th style="height: 5px; vertical-align: center;"><h3>Day 1</h3></th>
					<th style="height: 5px; vertical-align: center;"><!--<i class="far fa-clock"></i> 53--></th>
				</tr>
				<tr>
					<td id="game-content-l" style="width: 80%; padding: 0;">
						<div id="news-bar" style="background: #000; width: 99.5%; border-radius: 20px 20px 0px 0px; padding: 0.25%;">
							<p>Citizens of Riverdale, ... </p>
						</div>
						<div id="day-chat" style="width: 100%; height: 88%; background: #111; border-radius: 0px 0px 20px 20px;">
						</div>
					</td>
					<td id="game-content-r" style="width: 20%;">
						<?php
							session_start();
							$conn = new mysqli('localhost', 'root', '299792458', 'mafia');
							$query = "SELECT * FROM town_" . $_SESSION["townID"] . ";";
					
							if($result = mysqli_query($conn, $query)) {
								while($row = mysqli_fetch_assoc($result)) {
									$name = $row["name"];
									if($_SESSION["userID"] == $row["user_id"])
										$name = $name . " <b>(You)</b>";
								
									if($row["is_mafia"] && $row["is_executed"])
										$span = '<span style="color: #c80000; text-decoration: line-through;">';
									else if($row["is_poser"] && $row["is_executed"])
										$span = '<span style="color: #ffd300; text-decoration: line-through;">';
									else if($row["is_medic"] && $row["is_executed"])
										$span = '<span style="color: #fa691d; text-decoration: line-through;">';
									else if($row["is_sherrif"] && $row["is_executed"])
										$span = '<span style="color: #3895d3; text-decoration: line-through;">';
									else if($row["is_killed"])
										$span = '<span style="text-decoration: line-through;">';
									else
										$span = '<span>';
								
									echo $span . $name . '</span><br>';
								}

								echo '<hr style="border-style: solid; border-color: #936c6c; margin-top: 20px; margin-bottom: 20px;">';
						
								echo '<span>Population: ' . mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(user_id) FROM town_" . $_SESSION["townID"] . ";"))["COUNT(user_id)"] . '</span><br>';
								echo '<span style="color: #c80000;">Mafia: ' . mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(user_id) FROM town_" . $_SESSION["townID"] . " WHERE is_mafia = 1;"))["COUNT(user_id)"] . '</span><br>';
								echo '<span style="color: #ffd300;">Poser: ' . mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(user_id) FROM town_" . $_SESSION["townID"] . " WHERE is_poser = 1;"))["COUNT(user_id)"] . '</span><br>';
								echo '<span style="color: #fa691d;">Medic: ' . mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(user_id) FROM town_" . $_SESSION["townID"] . " WHERE is_medic = 1;"))["COUNT(user_id)"] . '</span><br>';
								echo '<span style="color: #3895d3;">Sherrif: ' . mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(user_id) FROM town_" . $_SESSION["townID"] . " WHERE is_sherrif = 1;"))["COUNT(user_id)"] . '</span><br>';
							}
						
							mysqli_close($conn);
						?>
					</td>
				</td>
			</table>
		</div>
		
		<div id="modal-background" onclick="closeAll()"></div>
		
		<div id="privacy-modal" class="modal" style="text-align: left;">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Privacy Policy</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closePrivacy()"></i></td>
			</table>
			<p>We don't store shit.</p>
		</div>
		
		<div id="bug-modal" class="modal">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Report Bug</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeBug()"></i></td>
			</table>
			<form id="bug-report" style="margin: 10px;">
				<textArea name="bug-report" class="text-box" style="margin-bottom: 10px;" placeholder="Write a bug report..."></textArea>
				<p id="success-bug" style="margin: 10px; margin-top: 0; margin-bottom: 0; color: #c80000; display: none;">Success! Your report has been submitted.</p>
				<p id="error-bug" style="margin: 10px; margin-top: 0; margin-bottom: 0; color: #c80000; display: none;">Error! Please try again later.</p>
				<button class="btn" type="submit" style="margin-top: 10px;">Submit Bug Report</button>
			</form>
		</div>
		
		<div id="about-modal" class="modal" style="text-align: left;">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">About Us</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAbout()"></i></td>
			</table>
			<p>Made with love by a team of talented people from <b>BinaryStack</b>.</p>
			<p>Built By: <b><a class="link2" href="https://instagram.com/abishek_devendran/">@AbishekDevendran</a></b> & <b><a class="link2" href="https://instagram.com/therealsujitk">@therealsujitk</a></b>.</p>
		</div>
		
		<div id="create-modal" class="modal">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Create a Town</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeCreate()"></i></td>
			</table>
			<form action="initialize-town.php" method="POST" style="margin: 0;">
				<table cellpadding="0" cellspacing="0" style="width: 100%;">
					<tr>
						<td style="padding-right: 1px;"><span>Town's name:</span></td>
						<td style="padding-left: 1px;"><input id="town" class="text-box" type="text" autocomplete="off" spellcheck="false" placeholder="(optional)" name="town"></input></td>
					</tr>
					<tr>
						<td style="padding-right: 1px;"><span>Mob's name:</span></td>
						<td style="padding-left: 1px;"><input id="mob" class="text-box" type="text" autocomplete="off" spellcheck="false" placeholder="(optional)" name="mob"></input></td>
					</tr>
				</table>
				<button class="btn" type="submit" style="margin: 10px;">Create Town</button></a>
			</form>
		</div>
		
		<div id="join-modal" class="modal">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Join a Town</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeJoin()"></i></td>
			</table>
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td style="padding-right: 1px;"><span>Town ID:</span></td>
				<td style="padding-left: 1px;"><input id="id" class="text-box" type="text" autocomplete="off" spellcheck="false"></input></td>
			</table>
			<a href=""><input class="btn" type="button" value="Join Town"  style="margin: 10px;"></input></a>
		</div>
		
		<script>
			$(document).ready(function(){
				setInterval(function(){
					$("#player-cards").load(window.location.href + " #player-cards > *" );
				}, 1000);
			});
		</script>
	</body>	
</html>

<!--UPDATE  `mafia`.`town_c14wj018sgcrp01` SET  `is_killed` =  '1' WHERE  `town_c14wj018sgcrp01`.`user_id` =6;-->

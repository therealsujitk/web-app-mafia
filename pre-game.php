<!DOCTYPE html public>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="assets/css/main.css">
		<link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
		<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
		<meta content="utf-8" http-equiv="encoding">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
		<script src="http://code.jquery.com/jquery-3.5.0.js"></script>
		<script src="assets/js/main.js"></script>
		<script>
			var townID = <?php session_start(); echo json_encode($_SESSION["townID"]); ?>;
			//var id = window.location.href;
			//id = id.replace('https://playmafia.cf/', '');
			//id = id.split('/')[0];
			//if (id != townID)
				//window.location.replace('https://playmafia.cf');
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
		
		<div id="town-players">
			<h2><?php
				session_start();
				include('conn.php');
				$townID = $_SESSION["townID"];
				$query = "SELECT town_name FROM town_details WHERE town_id = '$townID';";
				
				if($result = mysqli_query($conn, $query))
					echo mysqli_fetch_assoc($result)["town_name"];
				
				mysqli_close($conn);
			?></h2>
			<table id="player-cards" cellpadding="0" cellspacing="0">
				<?php
					session_start();
					$conn = mysqli_connect('sql300.epizy.com', 'epiz_25248784', 'oHooXKYBnmNP', 'epiz_25248784_mafia');
					$query = "SELECT * FROM town_" . $_SESSION["townID"] . ";";
					
					if($result = mysqli_query($conn, $query))
						while($row = mysqli_fetch_assoc($result)) {
							$name = $row["name"];
							if($_SESSION["userID"] == $row["user_id"])
								$name = $name . " (You)";
							echo '<td style="vertical-align: top; padding: 0;"><figure style="margin: 10px;"><img style="height: 150px;" src="'. $row["avatar"] .'"></img><figcaption><b>' . $name . '</b></figcaption></figure></td>';
						}
					
					mysqli_close($conn);
				?>
			</table>

			<p id="share">Your Town ID is <b><?php session_start(); echo $_SESSION["townID"]; ?></b>. <span class="link3" onclick="copyInvite(townID);">Click here</span> to copy the invite link.</p>
			<input id="start" class="btn" type="button" value="Start Game" ></input>
		</div>
		
		<div id="modal-background" onclick="closeAll()"></div>
		
		<div id="privacy-modal" class="modal" style="text-align: left;">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Privacy Policy</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closePrivacy()"></i></td>
			</table>
			<p>We use cookies to store your name and the avatar you selected.</p>
			<p>Details necessary to set up the game environment are stored in our database. All data stored is deleted shortly after the game ends.</p>
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
			setInterval(function(){
				$("#player-cards").load("https://playmafia.cf/pre-game.php" + " #player-cards > *" );
			}, 1000);
			
			$('#start').on('click', function (event) {

				$.ajax({
					type: 'POST',
					url: '../build-town.php',
					success: function () {
						window.location.href = '../play/' + townID + '/';
					},
					error: function () {
						//do something
					}
				});
			});
		</script>
	</body>	
</html>

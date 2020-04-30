<!DOCTYPE html public>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="/assets/css/main.css">
		<link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
		<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
		<meta content="utf-8" http-equiv="encoding">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
		<script src="http://code.jquery.com/jquery-3.5.0.js"></script>
		<script src="/assets/js/main.js"></script>
		<meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=0.7 maximum-scale=1.0, user-scalable=no">
		<script>
			<?php
				include('conn.php');
				session_start();
			?>
			
			var townID = <?php echo json_encode($_SESSION["townID"]); ?>;
			//var id = window.location.href;
			//id = id.replace('https://playmafia.cf/', '');
			//id = id.split('/')[0];
			//if (id != townID)
				//window.location.replace('https://playmafia.cf');
		</script>
		<title><?php echo $_SESSION["town"]; ?> - Mafia</title>
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
			<h2><?php echo $_SESSION["town"]; ?></h2>
			<table id="player-cards" cellpadding="0" cellspacing="0">
				<?php
					$query = "SELECT * FROM town_" . $_SESSION["townID"] . ";";
					
					if($result = mysqli_query($conn, $query))
						while($row = mysqli_fetch_assoc($result)) {
							$name = $row["name"];
							if($_SESSION["userID"] == $row["user_id"])
								$name = $name . " <b>(You)</b>";
							echo '<td style="vertical-align: top; padding: 0;"><figure style="margin: 10px;"><img style="height: 150px;" src="'. $row["avatar"] .'"></img><figcaption>' . $name . '</figcaption></figure></td>';
						}
				?>
			</table>

			<p id="share">Your Town ID is <b><?php echo $_SESSION["townID"]; ?></b>. <span class="link3" onclick="copyInvite(townID);">Click here</span> to copy.</p>
			<input id="start" class="btn" type="button" value="Start Game"></input>
		</div>
		
		<div id="modal-background" onclick="closeAll()"></div>
		
		<div id="privacy-modal" class="modal" style="text-align: left;">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Privacy Policy</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
			</table>
			<p>We use cookies to store your name and the avatar you selected.</p>
			<p>Details necessary to set up the game environment are stored in our database. All data stored is deleted shortly after the game ends.</p>
		</div>
		
		<div id="bug-modal" class="modal">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Report Bug</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
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
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
			</table>
			<p>Made with love by a team of talented people from <b>BinaryStack</b>.</p>
			<p>Built By: <b><a class="link2" href="https://instagram.com/abishek_devendran/">@AbishekDevendran</a></b> & <b><a class="link2" href="https://instagram.com/therealsujitk/">@therealsujitk</a></b>.</p>
		</div>
		
		<script>
			setInterval(function(){
				$("#player-cards").load("/pre-game.php" + " #player-cards > *" );
			}, 1000);
			
			$('#start').on('click', function (event) {
				$.ajax({
					type: 'POST',
					url: '/build-town.php',
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

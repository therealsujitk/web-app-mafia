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
		<link rel="apple-touch-icon" sizes="180x180" href="/assets/favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/assets/favicon/favicon-16x16.png">
		<link rel="manifest" href="/assets/favicon/site.webmanifest">
		<link rel="mask-icon" href="/assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="theme-color" content="#1f1414">
		<meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="user-scalable=no">
		<meta name="description" content="Mafia is a free multiplayer social deduction game. The game models a conflict between two groups: an informed minority, and an uninformed majority. At the start of the game, each player is secretly assigned a role affiliated with one of these teams.">
		<meta name="keywords" content="mafia, mafia cf, mafiacf, mafia.cf, cf, fun, winner, browser, free, friends">
		<meta name="robots" content="index">
		<meta http-equiv="content-language" content="en">
		<meta property="og:type" content="website">
		<meta property="og:title" content="Mafia - Free multiplayer social deduction game">
		<meta property="og:url" content="https://playmafia.cf/">
		<meta property="og:description" content="Mafia is a free multiplayer social deduction game. The game models a conflict between two groups: an informed minority, and an uninformed majority. At the start of the game, each player is secretly assigned a role affiliated with one of these teams.">
		<meta property="og:site_name" content="Mafia">
		<meta property="og:image" content="https://playmafia.cf/assets/images/thumbnail.png">
		<meta property="og:image:width" content="768">
		<meta property="og:image:height" content="435">
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:title" content="Mafia - Free multiplayer social deduction game">
		<meta name="twitter:description" content="Mafia is a free multiplayer social deduction game. The game models a conflict between two groups: an informed minority, and an uninformed majority. At the start of the game, each player is secretly assigned a role affiliated with one of these teams.">
		<meta name="twitter:image" content="https://playmafia.cf/assets/images/thumbnail.png">
		<title>Mafia</title>
	</head>
	<body>
		<?php
			include('conn.php');
			session_start();
			$townID = $_SESSION["townID"];
			if($townID) {
				$query = "SELECT user_id FROM town_" . $townID . ";";
				if(mysqli_query($conn, $query)) {
					$query = "SELECT has_started FROM town_details WHERE town_id = '$townID';";
					if(mysqli_fetch_assoc(mysqli_query($conn, $query))["has_started"]) {
						echo '<script>$("body").load("/game.php");</script>';
					}
					else {
						echo '<script>$("body").load("/pre-game.php");</script>';
					}
				}
			}
		?>
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
		<div align="center">
			<table id="user-details">
				<tr>
					<td>
						<img id="avatar" src="">
					</td>
					<td>
						<table cellpadding="0" cellspacing="0" width="100%">
							<td style="padding-right: 1px;"><span>Name:</span></td>
							<td style="padding-left: 1px;"><input id="name" class="text-box" type="text" autocomplete="off" spellcheck="false"></input></td>
						</table>
						<span id="name-error" style="padding-left: 10px; color: #c80000; display: none;">Error! Please enter a valid name.</span>
						<table cellpadding="0" cellspacing="0">
							<td><input class="btn" type="button" value="Create a Town" onclick="openCreate()"></input></td>
							<td><input class="btn" type="button" value="Join a Town" onclick="openJoin()"></input></td>
						</table>
					</td>
				</tr>
				<tr>
					<td style = "padding: 0;">
						<table cellpadding="0" cellspacing="0" width="100%">
							<td style = "text-align: right; padding: 0; padding-right: 15px;"><i class="btn2 fas fa-caret-left fa-3x" onclick="prev()"></i></td>
							<td style = "text-align: left; padding: 0; padding-left: 15px;"><i class="btn2 fas fa-caret-right fa-3x" onclick="next()"></i></input></td>
						</table>
					</td>
				</tr>
			</table>
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
			<div id="bug-report" style="margin: 10px;">
				<textArea id="report" class="text-box" placeholder="Write a bug report..."></textArea>
				<p id="success-bug" style="margin: 10px; margin-top: 0; margin-bottom: 0; color: #c80000; display: none;">Success! Your report has been submitted.</p>
				<input id="submit-report" class="btn" type="button" style="margin-top: 10px;" value="Submit Bug Report"></input>
			</div>
		</div>
		
		<div id="about-modal" class="modal" style="text-align: left;">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">About Us</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
			</table>
			<p>Made with love by a team of talented people from <b>BinaryStack</b>.</p>
			<p>Built By: <b><a class="link2" href="https://instagram.com/abishek_devendran/">@AbishekDevendran</a></b> & <b><a class="link2" href="https://instagram.com/therealsujitk">@therealsujitk</a></b>.</p>
		</div>
		
		<div id="create-modal" class="modal">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Create a Town</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
			</table>
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<tr>
					<td style="padding-right: 1px;"><span>Town's name:</span></td>
					<td style="padding-left: 1px;"><input id="town" name="town" class="text-box" type="text" autocomplete="off" spellcheck="false" placeholder="(optional)" name="town"></input></td>
				</tr>
				<tr>
					<td style="padding-right: 1px;"><span>Mob's name:</span></td>
					<td style="padding-left: 1px;"><input id="mob" name="mob" class="text-box" type="text" autocomplete="off" spellcheck="false" placeholder="(optional)" name="mob"></input></td>
				</tr>
			</table>
			<input id="create" class="btn" type="button" style="margin: 10px;" value="Create Town"></input>
		</div>
		
		<div id="join-modal" class="modal">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Join a Town</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
			</table>
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td style="padding-right: 1px;"><span>Town ID:</span></td>
				<td style="padding-left: 1px;"><input id="town-id" class="text-box" type="text" autocomplete="off" spellcheck="false"></input></td>
			</table>
			<input id="join" class="btn" type="button" style="margin: 10px;" value="Join Town"></input>
		</div>
		
		<div id="error-modal" class="modal">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Error!</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
			</table>
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td><img src="/assets/images/error.png" style="height: 50px;"></img></td>
				<td><p id="error-message" style="padding: 0; margin: 0;"></p></td>
			</table>
		</div>
		
		<script>
			let avatarID = Math.round(Math.random() * 12);
			if(avatarID < 10)
				document.getElementById('avatar').src = '/assets/avatars/avatar_0' + avatarID + '.png';
			else
				document.getElementById('avatar').src = '/assets/avatars/avatar_' + avatarID + '.png';
		
			function submitReport(response) {
				if(response === "Success!") {
					document.getElementById('success-bug').style.display = "block";
					document.getElementById('report').value = "";
					
					$('#submit-report').prop('disabled', false);
					$('#submit-report').val('Submit Bug Report');
				}
				else {
					closeAll();
					document.getElementById('error-message').innerHTML = response;
					document.getElementById('error-modal').classList.add("show-modal");;
					document.getElementById('modal-background').style.display = "block";
			
					$('#submit-report').prop('disabled', false);
					$('#submit-report').val('Submit Bug Report');
				}
			}

			$('#submit-report').on('click', function () {
				$('#submit-report').prop('disabled', true);
				$('#submit-report').val('Please wait...');
	
				let report = document.getElementById('report').value;

				$.ajax({
					type: 'POST',
					url: 'report-bug.php',
					data: {
						report: report
					}
				}).then(response => submitReport(response));
			});
			
			function createTown(response) {
				if(response === "Success!")
					$("body").load("/pre-game.php");
				else {
					closeAll();
					document.getElementById('error-message').innerHTML = response;
					document.getElementById('error-modal').classList.add("show-modal");;
					document.getElementById('modal-background').style.display = "block";
					
					$('#create').prop('disabled', false);
					$('#create').val('Create Town');
				}
			}
		
			$('#create').on('click', function () {
				$('#create').prop('disabled', true);
				$('#create').val('Please wait...');
			
				let town = document.getElementById('town').value;
				let mob = document.getElementById('mob').value;
				let name = document.getElementById('name').value;
				let avatar = document.getElementById('avatar').src;

				$.ajax({
					type: 'POST',
					url: 'initialize-town.php',
					data: {
						town: town,
						mob: mob,
						name: name,
						avatar: avatar
					}
				}).then(response => createTown(response));
			});
			
			function joinTown(response) {
				if(response === "Success!")
					$("body").load("/pre-game.php");
				else {
					closeAll();
					document.getElementById('error-message').innerHTML = response;
					document.getElementById('error-modal').classList.add("show-modal");;
					document.getElementById('modal-background').style.display = "block";
					document.getElementById('town-id').value = "";
					
					$('#join').prop('disabled', false);
					$('#join').val('Join Town');
				}
			}
			
			$('#join').on('click', function () {
				$('#join').prop('disabled', true);
				$('#join').val('Please wait...');
			
				let townID = document.getElementById('town-id').value;
				let name = document.getElementById('name').value;
				let avatar = document.getElementById('avatar').src;

				$.ajax({
					type: 'POST',
					url: 'join-town.php',
					data: {
						townID: townID,
						name: name,
						avatar: avatar
					}
				}).then(response => joinTown(response));
			});
		
			function getCookie(cname) {
				var cookieArr = document.cookie.split(";");
				for(var i = 0; i < cookieArr.length; i++) {
					var cookiePair = cookieArr[i].split("=");
					if(cname == cookiePair[0].trim()) {
						return decodeURIComponent(cookiePair[1]);
					}
				}

				return null;
			}
		
			if(getCookie('name') != null)
				document.getElementById('name').value = getCookie('name');
		
			if(getCookie('avatar') != null)
				document.getElementById('avatar').src = getCookie('avatar');
		</script>
	</body>	
</html>


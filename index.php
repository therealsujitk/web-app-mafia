<!DOCTYPE html public>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="/assets/css/main.css">
		<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
		<meta content="utf-8" http-equiv="encoding">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
		<script src="/node_modules/jquery/dist/jquery.min.js"></script>
		<script src="/assets/js/main.js"></script>
		<link rel="apple-touch-icon" sizes="180x180" href="/assets/favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/assets/favicon/favicon-16x16.png">
		<link rel="manifest" href="/assets/favicon/site.webmanifest">
		<link rel="mask-icon" href="/assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="theme-color" content="#c80000">
		<meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
		<meta name="description" content="Mafia is a free multiplayer social deduction game. The game models a conflict between two groups: an informed minority, and an uninformed majority. At the start of the game, each player is secretly assigned a role affiliated with one of these teams.">
		<meta name="keywords" content="mafia, mafia cf, mafiacf, mafia.cf, cf, fun, winner, browser, free, friends">
		<meta name="robots" content="index">
		<meta http-equiv="content-language" content="en">
		<meta property="og:type" content="website">
		<meta property="og:title" content="Mafia - Free Multiplayer Social Deduction Game">
		<meta property="og:url" content="https://playmafia.cf/">
		<meta property="og:description" content="Mafia is a free multiplayer social deduction game. The game models a conflict between two groups: an informed minority, and an uninformed majority. At the start of the game, each player is secretly assigned a role affiliated with one of these teams.">
		<meta property="og:site_name" content="Mafia">
		<meta property="og:image" content="https://playmafia.cf/assets/images/thumbnail.png">
		<meta property="og:image:width" content="768">
		<meta property="og:image:height" content="435">
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:title" content="Mafia - Free Multiplayer Social Deduction Game">
		<meta name="twitter:description" content="Mafia is a free multiplayer social deduction game. The game models a conflict between two groups: an informed minority, and an uninformed majority. At the start of the game, each player is secretly assigned a role affiliated with one of these teams.">
		<meta name="twitter:image" content="https://playmafia.cf/assets/images/thumbnail.png">
		<title>Mafia - Free Multiplayer Social Deduction Game</title>
	</head>
	<body>
		<div id="header">
			<table cellpadding="0" cellspacing="0" style="width: 100%; padding-left: 1%; padding-right: 1%;">
				<td><img src="/assets/images/logo.png" style="height: 65px;"></img></td>
				<td style="text-align: right; vertical-align: top; padding-top: 20px;">
					<nav>
						<input class="header link" style="padding-left: 10px; padding-right: 10px;" type="button" value="Privacy Policy" onclick="openPrivacy()"></input>
						<input class="header link" style="padding-left: 10px; padding-right: 10px;" type="button" value="Report Bug" onclick="openBug()"></input>
						<input class="header link" style="padding-left: 10px; padding-right: 10px;" type="button" value="About Us" onclick="openAbout()"></input>
						<?php
						include('conn.php');
						session_start();
						
						if(isset($_SESSION["townID"])) {
							$townID = $_SESSION["townID"];
						
							$query = "SELECT user_id FROM town_" . $townID . ";";
							if(mysqli_query($conn, $query)) {
								$query = "SELECT has_started FROM town_details WHERE town_id = '$townID';";
								if(mysqli_fetch_assoc(mysqli_query($conn, $query))["has_started"]) {
									echo '<input class="header link" style="padding-left: 10px; padding-right: 10px;" type="button" value="Continue Playing" onclick="continuePlaying()"></input>';

									echo '<script>
										function continuePlaying() {
											conn.send("' . $townID . '")
											$("body").load("/game.php");
										}
									</script>';
								}
								else if(!mysqli_fetch_assoc(mysqli_query($conn, $query))["has_started"]) {
									echo '<input class="header link" style="padding-left: 10px; padding-right: 10px;" type="button" value="Continue Playing" onclick="continuePlaying()"></input>';
									
									echo '<script>
										function continuePlaying() {
											conn.send("' . $townID . '")
											$("body").load("/lobby.php");
										}
									</script>';
								}
							}
							else {
								session_unset();
								session_destroy();
							}
						}
						?>
					</nav>
				</td>
			</table>
		</div>

		<div id="header-mobile">
			<i id="menu-mobile" class="fas fa-bars" onclick="openMenu();"></i>
			<div id="logo-mobile"><img src="/assets/images/logo.png" style="height: 65px;"></img></div>
			<nav id="nav-mobile" class="nav">
				<table cellpadding="0" cellspacing="0" style="width: 100%; margin-top: 20px;">
					<td class="header2" style="text-align: left;"><img src="/assets/images/logo.png" style="height: 40px;"></img></td>
					<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeMenu()"></i></td>
				</table>
				<input class="header link" style="padding: 10px 20px 10px 20px;" type="button" value="Privacy Policy" onclick="openPrivacy()"></input>
				</br>
				<input class="header link" style="padding: 10px 20px 10px 20px;" type="button" value="Report Bug" onclick="openBug()"></input>
				</br>
				<input class="header link" style="padding: 10px 20px 10px 20px;" type="button" value="About Us" onclick="openAbout()"></input>
				</br>
				<?php
				if(isset($_SESSION["townID"])) {
					$townID = $_SESSION["townID"];
				
					$query = "SELECT user_id FROM town_" . $townID . ";";
					if(mysqli_query($conn, $query))
						echo '<input class="header link" style="padding: 10px 20px 10px 20px;" type="button" value="Continue Playing" onclick="continuePlaying()"></input>';
				}
				?>
			</nav>
		</div>

		<div>
			<table id="user-details" cellpadding="0" cellspacing="0">
				<tr>
					<td style="text-align: center">
						<i class="btn2 fas fa-caret-left fa-3x prev-next-mobile" onclick="prev()"></i>
						<img id="avatar">
						<i class="btn2 fas fa-caret-right fa-3x prev-next-mobile" onclick="next()"></i>
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_01.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_02.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_03.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_04.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_05.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_06.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_07.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_08.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_09.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_10.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_11.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_12.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_13.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_14.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_15.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_16.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_17.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_18.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_19.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_20.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_21.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_22.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_23.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_24.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_25.png">
						<img class="cache" style="display: none;" src="/assets/avatars/avatar_26.png">
					</td>
					<td id="details">
						<table cellpadding="0" cellspacing="0" width="100%">
							<td style="padding-right: 1px;"><span>Name:</span></td>
							<td style="padding-left: 1px;"><input id="name" class="text-box" type="text" autocomplete="off" spellcheck="false" maxlength = "10"></input></td>
						</table>
						<span id="name-error" style="padding-left: 10px; color: #c80000; display: none;">Error! Please enter a valid name.</span>
						<table id="create-join" cellpadding="0" cellspacing="0">
							<td><input class="btn" type="button" value="Create a Town" onclick="openCreate(1)"></input></td>
							<td><input class="btn" type="button" value="Join a Town" onclick="openJoin(1)"></input></td>
						</table>
					</td>
				</tr>
				<tr>
					<td id="prev-next" style="padding: 0;">
						<table cellpadding="0" cellspacing="0" width="100%">
							<td style = "text-align: right; padding: 0; padding-right: 15px;"><i class="btn2 fas fa-caret-left fa-3x" onclick="prev()"></i></td>
							<td style = "text-align: left; padding: 0; padding-left: 15px;"><i class="btn2 fas fa-caret-right fa-3x" onclick="next()"></i></input></td>
						</table>
					</td>
				</tr>
				<tr id="details-mobile">
					<td>
						<table cellpadding="0" cellspacing="0" width="100%">
							<td style="padding-right: 1px;"><span>Name:</span></td>
							<td style="padding-left: 1px;"><input id="name-mobile" class="text-box" type="text" autocomplete="off" spellcheck="false" maxlength = "10"></input></td>
						</table>
						<span id="name-error-mobile" style="padding-left: 20px; color: #c80000; display: none;">Error! Please enter a valid name.</span>
						<table id="create-join-mobile" cellpadding="0" cellspacing="0">
							<td><input class="btn" type="button" value="Create a Town" onclick="openCreate(0)"></input></td>
							<td><input class="btn" type="button" value="Join a Town" onclick="openJoin(0)"></input></td>
						</table>
					</td>
				</tr>
			</table>
		</div>
		
		<div id="menu-background" onclick="closeMenu()"></div>
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
				<input id="submit-report" class="btn" type="button" style="margin-top: 10px;" value="Submit Bug Report" onclick="reportBug();"></input>
			</div>
		</div>
		
		<div id="about-modal" class="modal" style="text-align: left;">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">About Us</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
			</table>
			<p>Made with love by a team of talented people from <b>BinaryStack</b>.</p>
			<p>Built By: <b><a class="link2" href="https://instagram.com/abishek.stuff/" target="_blank">@AbishekDevendran</a></b> & <b><a class="link2" href="https://therealsuji.tk" target="_blank">@therealsujitk</a></b>.</p>
			<div id="version"><span>v3.0.0</span></div>
		</div>
		
		<div id="create-modal" class="modal">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Create a Town</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
			</table>
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<tr>
					<td style="padding-right: 1px;"><span>Town's name:</span></td>
					<td style="padding-left: 1px;"><input id="town" name="town" class="text-box" type="text" autocomplete="off" spellcheck="false" placeholder="(optional)" name="town" maxlength = "20"></input></td>
				</tr>
				<tr>
					<td style="padding-right: 1px;"><span>Mob's name:</span></td>
					<td style="padding-left: 1px;"><input id="mob" name="mob" class="text-box" type="text" autocomplete="off" spellcheck="false" placeholder="(optional)" name="mob" maxlength = "20"></input></td>
				</tr>
			</table>
			<input id="create" class="btn" type="button" style="margin: 10px;" value="Create Town" onclick="createTown();"></input>
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
			<input id="join" class="btn" type="button" style="margin: 10px;" value="Join Town" onclick="joinTown();"></input>
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

		<div id="splash-background" class="splash-background"></div>

		<div id="splash" class="splash">
			<img id="splash-logo" src="/assets/images/logo.png"></img>
			</br></br>
			<img src="/assets/images/loading.gif"></img>
		</div>
				
		<script>
			setIndex();

			<?php
			if(isset($_GET["i"])) {
				$query = "SELECT town_name FROM town_details WHERE town_id = '" . $_GET["i"] . "';";
				if($result = mysqli_fetch_assoc(mysqli_query($conn, $query))) {
					$townName = $result["town_name"];
					echo "document.getElementById('create-join').innerHTML = `<td><input id='join-town' class='btn' style='width: 300px;' type='button' value='Join " . $townName . "' onclick='joinTown(1)'></input></td>`;";
					echo "document.getElementById('create-join-mobile').innerHTML = `<td><input id='join-town-mobile' class='btn' style='width: 300px;' type='button' value='Join " . $townName . "' onclick='joinTown(0)'></input></td>`;";
					echo "document.getElementById('town-id').value = '" . $_GET["i"] . "';";
				}
			}

			mysqli_close($conn);
			?>
		</script>
	</body>	
</html>
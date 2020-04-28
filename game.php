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
			var town = <?php session_start(); echo json_encode($_SESSION["town"]); ?>;
			var mob = <?php session_start(); echo json_encode($_SESSION["mob"]); ?>;
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
					<th style="height: 5px; vertical-align: center;"><h3 id="game-index"></h3></th>
					<th style="height: 5px; vertical-align: center;"><!--<i class="far fa-clock"></i> 53--></th>
				</tr>
				<tr>
					<td id="game-content-l" style="width: 80%; padding: 0;">
						<div id="content" style="width: 100%; height: 100%; background: #111; border-radius: 20px;">
							<div id="news-bar" style="background: #000; width: 99.5%; border-radius: 20px 20px 0px 0px; padding: 0.25%;">
								<p id="news">News from your town will show up here.</p>
							</div>
							<div id="game-display" style="height: 42vh; overflow-y: scroll; overflow-x: hidden;">
								<?php
									session_start();
									include('conn.php');
									$townID = $_SESSION["townID"];
									$query = "SELECT owner_id, game_index FROM town_details WHERE town_id = '$townID';";
									$ownerID = mysqli_fetch_assoc(mysqli_query($conn, $query))["owner_id"];
									$gameIndex = mysqli_fetch_assoc(mysqli_query($conn, $query))["game_index"];

									$query = "SELECT * FROM town_" . $_SESSION["townID"] . ";";
												
									if($gameIndex%2 == 0) {
										//Night
										$query = "SELECT user_id FROM town_" . $_SESSION["townID"] . " WHERE is_mafia = 1 AND is_killed = 0 AND is_executed = 0;";
										if($result = mysqli_query($conn, $query)) {
											while($row = mysqli_fetch_assoc($result)) {
												if($row["user_id"] == $_SESSION["userID"]) {
													$query = "SELECT * FROM chat_" . $_SESSION["townID"] . ";";
													if($result = mysqli_query($conn, $query)) {
														while($row = mysqli_fetch_assoc($result)) {
															if($row["name"] == $_SESSION["name"])
																echo '<div style="100%; text-align: right;"><div class="chat-r"><b>' . $row["name"] . ':</b> ' . $row["message"] . '</div></div>';
															else
																echo '<div style="100%; text-align: left;"><div class="chat-l"><b>' . $row["name"] . ':</b> ' . $row["message"] . '</div></div>';
														}
													}
													break;
												}
												else {
													//show image for non-mafia
												}
											}
										}
									}
									else {
										//Day
										$query = "SELECT user_id FROM town_" . $_SESSION["townID"] . " is_killed = 0 AND is_executed = 0;";
										if($result = mysqli_query($conn, $query)) {
											while($row = mysqli_fetch_assoc($result)) {
												if($row["user_id"] == $_SESSION["userID"]) {
													$query = "SELECT * FROM chat_" . $_SESSION["townID"] . ";";
													if($result = mysqli_query($conn, $query)) {
														while($row = mysqli_fetch_assoc($result)) {
															if($row["name"] == $_SESSION["name"])
																echo '<div style="100%; text-align: right;"><div class="chat-r"><b>' . $row["name"] . ':</b> ' . $row["message"] . '</div></div>';
															else
																echo '<div style="100%; text-align: left;"><div class="chat-l"><b>' . $row["name"] . ':</b> ' . $row["message"] . '</div></div>';
														}
													}
													break;
												}
												else {
													//show image for dead
												}
											}
										}
										
									}
									mysqli_close($conn);
								?>
							</div>
							<table style="width: 100%;">
								<td style="padding: 0;"><input id="chat-box" placeholder="Write a message..."></input></td>
								<td style="padding: 0;"><button id="send" class="btn" style="margin-right: 1.5%; border-radius: 10px;">Send</button></td>
							</table>
						</div>
					</td>
					<td id="game-content-r" style="width: 20%;">
						<?php
							session_start();
							include('conn.php');
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
									else if($row["is_executed"])
										$span = '<span style="text-decoration: line-through;">';
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
			<div id="day-night">
				<?php
					session_start();
					include('conn.php');
					$townID = $_SESSION["townID"];
					$query = "SELECT owner_id, game_index FROM town_details WHERE town_id = '$townID';";
					$ownerID = mysqli_fetch_assoc(mysqli_query($conn, $query))["owner_id"];
					$gameIndex = mysqli_fetch_assoc(mysqli_query($conn, $query))["game_index"];
					$town = $_SESSION["town"];
		
					$query = "SELECT * FROM town_" . $_SESSION["townID"] . ";";									
					if($gameIndex%2 == 0) {
						//Night
						$night = $gameIndex/2;
			
						if(!$night) {
							echo '<script>document.getElementsByTagName("title")[0].innerHTML = "The First Night • ' . $town . ' - Mafia";</script>';
							echo '<script>document.getElementById("game-index").innerHTML = "The First Night";</script>';
						}
						else {
							echo '<script>document.getElementsByTagName("title")[0].innerHTML = "Night ' . $night . ' • ' . $town . ' - Mafia";</script>';
							echo '<script>document.getElementById("game-index").innerHTML = "Night ' . $night . '";</script>';
						}
			
						echo '<script>
							document.getElementById("news").innerHTML = "";
							let news = "Welcome, members of " + mob + ", below you can chat with the other mafia members on who is to be killed. Mr. Bean will then choose the victim by clicking the button at the top right.";
							displayNews(news, 0);
						</script>';
					}
					else {
						//Day
						$day = $gameIndex/2 + 0.5;
						echo '<script>document.getElementsByTagName("title")[0].innerHTML = "Day ' . $day . ' • ' . $town . ' - Mafia";</script>';
						echo '<script>document.getElementById("game-index").innerHTML = "Day ' . $day . '";</script>';
						echo '<script>
							document.getElementById("news").innerHTML = "";
							let news = "Citizens of " + town + ", I\'m afraid I have some bad news. Last night " + mob + " struck again and killed Teddy. Below you can talk about who you suspect of this heinous crime. You can then secretly vote on who you think should be executed by clicking the button at the top right.";
							displayNews(news, 0);
						</script>';
					}
					mysqli_close($conn);
				?>
			</div>
		</div>
		
		<div id="modal-background" onclick="closeAll()"></div>
		<div id="modal-background2"></div>
		
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
			<p>Built By: <b><a class="link2" href="https://instagram.com/abishek_devendran/">@AbishekDevendran</a></b> & <b><a class="link2" href="https://instagram.com/therealsujitk">@therealsujitk</a></b>.</p>
		</div>
		
		<?php
			session_start();
			include('conn.php');
			$query = "SELECT * FROM town_" . $_SESSION["townID"] . " WHERE user_id = " . $_SESSION["userID"] . ";";
			
			if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_mafia"]) {
				echo '<div id="role-modal" class="modal" style="text-align: left;">
					<table cellpadding="0" cellspacing="0" style="width: 100%;">
						<td class="header2" style="text-align: left;">Your Role</td>
						<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
					</table>
					<table cellpadding="0" cellspacing="0" style="width: 100%;">
						<td><img src="assets/cards/mafia.png" style="height: 150px;"></img></td>
						<td><h3>Mafia</h3><br><p style="padding: 0; margin: 0;">Your job is to take over <b>' . $_SESSION["town"] . '</b> with the help of the other mafia members by killing citizens every night.</p></td>
					</table>
				</div>';
				
				echo '<script>document.getElementById("role-modal").classList.add("show-modal"); document.getElementById("modal-background2").style.display = "block";</script>';
			}
			else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_poser"]) {
				echo '<div id="role-modal" class="modal" style="text-align: left;">
					<table cellpadding="0" cellspacing="0" style="width: 100%;">
						<td class="header2" style="text-align: left;">Your Role</td>
						<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
					</table>
					<table cellpadding="0" cellspacing="0" style="width: 100%;">
						<td><img src="assets/cards/poser.png" style="height: 150px;"></img></td>
						<td><h3>Poser</h3><br><p style="padding: 0; margin: 0;">Your job is to help the' . $_SESSION["mob"] . ' mafia take over <b>' . $_SESSION["town"] . '</b> by making people think that you are a member of the mafia.</p></td>
					</table>
				</div>';
				
				echo '<script>document.getElementById("role-modal").classList.add("show-modal"); document.getElementById("modal-background2").style.display = "block";</script>';
			}
			else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_medic"]) {
				echo '<div id="role-modal" class="modal" style="text-align: left;">
					<table cellpadding="0" cellspacing="0" style="width: 100%;">
						<td class="header2" style="text-align: left;">Your Role</td>
						<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
					</table>
					<table cellpadding="0" cellspacing="0" style="width: 100%;">
						<td><img src="assets/cards/medic.png" style="height: 150px;"></img></td>
						<td><h3>Medic</h3><br><p style="padding: 0; margin: 0;">You have the ability to save anyone you want, you can even save yourself. Note that you <b>can not</b> save the same person twice in a row.</p></td>
					</table>
				</div>';
				
				echo '<script>document.getElementById("role-modal").classList.add("show-modal"); document.getElementById("modal-background2").style.display = "block";</script>';
			}
			else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_sherrif"]) {
				echo '<div id="role-modal" class="modal" style="text-align: left;">
					<table cellpadding="0" cellspacing="0" style="width: 100%;">
						<td class="header2" style="text-align: left;">Your Role</td>
						<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
					</table>
					<table cellpadding="0" cellspacing="0" style="width: 100%;">
						<td><img src="assets/cards/sherrif.png" style="height: 150px;"></img></td>
						<td><h3>Sherrif</h3><br><p style="padding: 0; margin: 0;">You have the ability to find out whether a citizen is a mafia member or not. You can use this knowledge by convincing citizens to execute mafia members.</p></td>
					</table>
				</div>';
				
				echo '<script>document.getElementById("role-modal").classList.add("show-modal"); document.getElementById("modal-background2").style.display = "block";</script>';
			}
			else {
				echo '<div id="role-modal" class="modal" style="text-align: left;">
					<table cellpadding="0" cellspacing="0" style="width: 100%;">
						<td class="header2" style="text-align: left;">Your Role</td>
						<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
					</table>
					<table cellpadding="0" cellspacing="0" style="width: 100%;">
						<td><img src="assets/cards/citizen.png" style="height: 150px;"></img></td>
						<td><h3>Citizen</h3><br><p style="padding: 0; margin: 0;">Your job is to vote on who is to be executed every day, make sure you vote on mafia members or you may lose.</p></td>
					</table>
				</div>';
				
				echo '<script>document.getElementById("role-modal").classList.add("show-modal"); document.getElementById("modal-background2").style.display = "block";</script>';
			}
		?>
		
		<script>
			$('#send').on('click', function (event) {
				
				let message = document.getElementById('chat-box').value;

				$.ajax({
					type: 'POST',
					url: 'send-message.php',
					data: {
						message: message
					},
					success: function () {
						document.getElementById('chat-box').value = "";
					},
					error: function () {
						//do something
					}
				});
			});
		
			scrolled = false;
			var elem = document.getElementById("game-display");
			setInterval(function(){
				//$("#player-cards").load("https://playmafia.cf/game.php" + " #player-cards > *" );
				$("#game-display").load("http://binarystack.localhost/mafia/game.php" + " #game-display > *" );
				if(!scrolled) {
					elem.scrollTop = elem.scrollHeight;
				}
			}, 1000);	
			$("#game-display").on('scroll', function(){
				if(elem.scrollTop + elem.clientHeight == elem.scrollHeight)
					scrolled=false;
				else
					scrolled = true;
			});
			
			setInterval(function(){
				$("#game-content-r").load("http://binarystack.localhost/mafia/game.php" + " #game-content-r > *" );
			}, 10000);
		</script>
	</body>	
</html>

<!--UPDATE  `mafia`.`town_c14wj018sgcrp01` SET  `is_killed` =  '1' WHERE  `town_c14wj018sgcrp01`.`user_id` =6;-->

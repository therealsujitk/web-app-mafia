<script>
	<?php
		include('conn.php');
		session_start();
		$townID = $_SESSION["townID"];
		$userID = $_SESSION["userID"];
		$town = $_SESSION["town"];
		$mob = $_SESSION["mob"];
		$query = "SELECT daily_index FROM town_details WHERE town_id = '$townID';";
		$dailyIndex = mysqli_fetch_assoc(mysqli_query($conn, $query))["daily_index"];
	?>

	var townID = <?php echo json_encode($townID); ?>;
	var town = <?php echo json_encode($town); ?>;
	var mob = <?php echo json_encode($mob); ?>;
</script>
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
			<th style="height: 5px; vertical-align: center;"><input id="my-role" class="btn" type="button" value="My Role" onclick="openRole()"></input></th>
		</tr>
		<tr>
			<td id="game-content-l" style="width: 80%; padding: 0;">
				<div id="content" style="width: 100%; height: 100%; background: #111; border-radius: 20px;">
					<div id="news-bar" style="background: #000; width: 99.5%; border-radius: 20px 20px 0px 0px; padding: 0.25%;">
						<p id="news">News from your town will show up here.</p>
					</div>
					<div id="game-display" style="height: 35vh; overflow-y: scroll; overflow-x: hidden;">
						<?php
							if(!$dailyIndex%2) {
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
								$query = "SELECT * FROM chat_" . $townID . ";";
								if($result = mysqli_query($conn, $query)) {
									while($row = mysqli_fetch_assoc($result)) {
										if($row["name"] == $_SESSION["name"])
											echo '<div style="100%; text-align: right;"><div class="chat-r"><b>' . $row["name"] . ':</b> ' . $row["message"] . '</div></div>';
										else
											echo '<div style="100%; text-align: left;"><div class="chat-l"><b>' . $row["name"] . ':</b> ' . $row["message"] . '</div></div>';
									}
								}
							}
						?>
					</div>
					<table style="width: 100%;">
						<td style="padding: 0;"><input id="chat-box" placeholder="Write a message..."></input></td>
						<td style="padding: 0; width: 190px;"><input id="send" class="btn" type="button" style="border-radius: 10px;" value="Send"></input><input id="vote" class="btn" type="button" style="margin-left: 6%; margin-right: 1.5%; border-radius: 10px;" value="Vote"></input></td>
					</table>
				</div>
			</td>
			<td id="game-content-r" style="width: 20%;">
				<div id="players">
					<?php
						$query = "SELECT * FROM town_" . $_SESSION["townID"] . ";";
						if($result = mysqli_query($conn, $query)) {
							while($row = mysqli_fetch_assoc($result)) {
								$name = $row["name"];
								if($_SESSION["userID"] == $row["user_id"])
									$name = $name . " <b>(You)</b>";
						
								if($row["is_mafia"] && $row["is_executed"])
									$span = '<span style="font-weight: normal; color: #c80000; text-decoration: line-through;">';
								else if($row["is_poser"] && $row["is_executed"])
									$span = '<span style="font-weight: normal; color: #ffd300; text-decoration: line-through;">';
								else if($row["is_medic"] && $row["is_executed"])
									$span = '<span style="font-weight: normal; color: #fa691d; text-decoration: line-through;">';
								else if($row["is_sherrif"] && $row["is_executed"])
									$span = '<span style="font-weight: normal; color: #3895d3; text-decoration: line-through;">';
								else if($row["is_executed"])
									$span = '<span style="font-weight: normal; text-decoration: line-through;">';
								else if($row["is_killed"])
									$span = '<span style="font-weight: normal; text-decoration: line-through;">';
								else
									$span = '<span style="font-weight: normal;">';
						
								echo $span . $name . '</span><br>';
							}
						}
					?>
				</div>
				
				<div id="town-details">
					<?php
						if($result = mysqli_query($conn, $query)) {
							echo '<hr style="border-style: solid; border-color: #936c6c; margin-top: 20px; margin-bottom: 20px;">';
							echo '<span>Population: ' . mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(user_id) FROM town_" . $_SESSION["townID"] . ";"))["COUNT(user_id)"] . '</span><br>';
							echo '<span style="color: #c80000;">Mafia: ' . mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(user_id) FROM town_" . $_SESSION["townID"] . " WHERE is_mafia = 1;"))["COUNT(user_id)"] . '</span><br>';
							echo '<span style="color: #ffd300;">Poser: ' . mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(user_id) FROM town_" . $_SESSION["townID"] . " WHERE is_poser = 1;"))["COUNT(user_id)"] . '</span><br>';
							echo '<span style="color: #fa691d;">Medic: ' . mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(user_id) FROM town_" . $_SESSION["townID"] . " WHERE is_medic = 1;"))["COUNT(user_id)"] . '</span><br>';
							echo '<span style="color: #3895d3;">Sherrif: ' . mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(user_id) FROM town_" . $_SESSION["townID"] . " WHERE is_sherrif = 1;"))["COUNT(user_id)"] . '</span><br>';
						}
					?>
				</div>
			</td>
		</td>
	</table>
	<div id="daily-update">
		<?php
			if(!$dailyIndex%2) {
				$night = $dailyIndex/2;
				if(!$night) {
					echo '<script>document.getElementsByTagName("title")[0].innerHTML = "The First Night • ' . $town . ' - Mafia";</script>';
					echo '<script>document.getElementById("game-index").innerHTML = "The First Night";</script>';
					
					$query = "SELECT user_id FROM town_" . $townID . " WHERE is_poser = 1;";
					if($result = mysqli_query($conn, $query)) {
						while($row = mysqli_fetch_assoc($result)) {
							if($row["user_id"] == $userID) {
								echo '<script>
									document.getElementById("news").innerHTML = "";
									let news = "Message for poser.";
									displayNews(news, 0);
								</script>';
								echo '<script>document.getElementById("vote").disabled = false;</script>';
								echo '<script>document.getElementById("vote").innerHTML = "Heal";</script>';
								break;
							}
							else {
								echo '<script>document.getElementById("send").disabled = true;</script>';
								echo '<script>document.getElementById("vote").disabled = true;</script>';
								echo '<script>document.getElementById("vote").innerHTML = "Vote";</script>';
							}
						}
					}
				}
				else {
					echo '<script>document.getElementsByTagName("title")[0].innerHTML = "Night ' . $night . ' • ' . $town . ' - Mafia";</script>';
					echo '<script>document.getElementById("game-index").innerHTML = "Night ' . $night . '";</script>';
				}

				$query = "SELECT user_id FROM town_" . $townID . " WHERE is_mafia = 1;";
				if($result = mysqli_query($conn, $query)) {
					while($row = mysqli_fetch_assoc($result)) {
						if($row["user_id"] == $userID) {
							echo '<script>
								document.getElementById("news").innerHTML = "";
								let news = "Welcome, members of *" + mob + "*, below you can chat with the other mafia members on who is to be killed. *Mr. Bean* will then choose the victim by clicking the *Kill* button at the bottom.";
								displayNews(news, 0);
							</script>';
							echo '<script>document.getElementById("send").disabled = false;</script>';
							echo '<script>document.getElementById("vote").disabled = false;</script>';
							echo '<script>document.getElementById("vote").innerHTML = "Kill";</script>';
							break;
						}
						else {
							echo '<script>document.getElementById("send").disabled = true;</script>';
							echo '<script>document.getElementById("vote").disabled = true;</script>';
							echo '<script>document.getElementById("vote").innerHTML = "Vote";</script>';
						}
					}
				}

				$query = "SELECT user_id FROM town_" . $townID . " WHERE is_medic = 1;";
				if($result = mysqli_query($conn, $query)) {
					while($row = mysqli_fetch_assoc($result)) {
						if($row["user_id"] == $userID) {
							echo '<script>
								document.getElementById("news").innerHTML = "";
								let news = "Message for medic.";
								displayNews(news, 0);
							</script>';
							echo '<script>document.getElementById("vote").disabled = false;</script>';
							echo '<script>document.getElementById("vote").innerHTML = "Heal";</script>';
							break;
						}
						else {
							echo '<script>document.getElementById("send").disabled = true;</script>';
							echo '<script>document.getElementById("vote").disabled = true;</script>';
							echo '<script>document.getElementById("vote").innerHTML = "Vote";</script>';
						}
					}
				}
			}
			else {
				$day = $dailyIndex/2 + 0.5;
				echo '<script>document.getElementsByTagName("title")[0].innerHTML = "Day ' . $day . ' • ' . $town . ' - Mafia";</script>';
				echo '<script>document.getElementById("game-index").innerHTML = "Day ' . $day . '";</script>';
				echo '<script>
					document.getElementById("news").innerHTML = "";
					let news = "Citizens of *" + town + "*, I\'m afraid I have some bad news. Last night *" + mob + "* struck again and killed *Teddy*. Below you can talk about who you suspect of this heinous crime. You can then secretly vote on who you think should be executed by clicking the *Vote* button at the bottom.";
					displayNews(news, 0);
				</script>';
				echo '<script>document.getElementById("send").disabled = false;</script>';
				echo '<script>document.getElementById("vote").disabled = false;</script>';
				echo '<script>document.getElementById("vote").innerHTML = "Vote";</script>';
			}
			
			$query = "SELECT user_id FROM town_" . $townID . " WHERE is_killed = 1 OR is_executed = 1;";
			if($result = mysqli_query($conn, $query)) {
				while($row = mysqli_fetch_assoc($result)) {
					if($row["user_id"] == $userID) {
						echo '<script>document.getElementById("send").disabled = true;</script>';
						echo '<script>document.getElementById("vote").disabled = true;</script>';
						break;
					}
				}
			}
		?>
	</div>
	<div id="game-update">
		<?php
			$query = "SELECT daily_index FROM town_details WHERE town_id = '$townID';";
			$tempIndex = mysqli_fetch_assoc(mysqli_query($conn, $query))["daily_index"];
			
			if($dailyIndex != $tempIndex) {
				if(!$tempIndex%2) {
					$prev = $dailyIndex/2 + 0.5;
					$night = $tempIndex/2;
				
					$executed = '';
					
					$query = "SELECT COUNT(day_" . $prev . "), day_" . $prev . " FROM town_" . $townID . " WHERE day_" . $prev . " <> 0 GROUP BY day_" . $prev . ";";
					if($result = mysqli_query($conn, $query)) {
						while($row = mysqli_fetch_assoc($result)) {
							if($row["COUNT(day_" . $day . ")"] >= $dayVote/2) {
								$query = "SELECT name, day_" . $day . " FROM town_" . $townID . " WHERE day_" . $day . " = " . $row["day_" . $day] . ";";
								$executed = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
								break;
							}
						}
					}
				
					if($executed != "") {
						$query = "UPDATE town_" . $townID . " SET in_executed = 1 WHERE name = '$executed';";
						mysqli_query($conn, $query);
					}
				
					$query = "ALTER TABLE town_" . $townID . " ADD night_" . $night . " INT(1) NOT NULL DEFAULT 0;";
					mysqli_query($conn, $query);

					$query = "ALTER TABLE town_" . $townID . " ADD medic_" . $night . " INT(1) NOT NULL DEFAULT 0;";
					mysqli_query($conn, $query);

					$day = $night + 1;
					$query = "ALTER TABLE town_" . $_SESSION["townID"] . " ADD day_" . $day . " INT(2) NOT NULL DEFAULT 0;";
					mysqli_query($conn, $query);
				
					$query = "UPDATE town_details SET game_index = 0 WHERE town_id = '$townID';";
					mysqli_query($conn, $query);
				
					$query = "SELECT COUNT(name) FROM town_" . $townID . " WHERE is_medic = 1 AND is_killed = 0 AND is_executed = 0;";
					$max = mysqli_fetch_assoc(mysqli_query($conn, $query))["COUNT(name)"] * 2 + 1;
					$query = "UPDATE town_details SET daily_max = " . $max . " WHERE town_id = '$townID';";
					mysqli_query($conn, $query);
					
					$dailyIndex = $tempIndex;
				}
				else {
					$prev = $dailyIndex/2;
					$day = $tempIndex/2 + 0.5;
					
					$query = "SELECT name FROM town_" . $townID . " WHERE night_" . $prev . " <> 0;";
					$killed = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
					$query = "SELECT name FROM town_" . $townID . " WHERE medic_" . $prev . " <> 0;";
					$healed = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
				
					if($killed != $healed) {
						$query = "UPDATE town_" . $townID . " SET is_killed = 1 WHERE name = '$killed';";
						mysqli_query($conn, $query);
					}
					
					$query = "UPDATE town_details SET game_index = 0 WHERE town_id = '$townID';";
					mysqli_query($conn, $query);
					
					$query = "SELECT COUNT(name) FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0;";
					$max = mysqli_fetch_assoc(mysqli_query($conn, $query))["COUNT(name)"];
					$query = "UPDATE town_details SET daily_max = " . $max . " WHERE town_id = '$townID';";
					mysqli_query($conn, $query);
					
					$dailyIndex = $tempIndex;
				}
				
			}
		?>
	</div>
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
		<p id="error-bug" style="margin: 10px; margin-top: 0; margin-bottom: 0; color: #c80000; display: none;">Error! Please try again later.</p>
		<input id="submit-report" class="btn" type="button" style="margin-top: 10px;" value="Submit Bug Report"></input>
	</div>
</div>

<div id="about-modal" class="modal" style="text-align: left;">
	<table cellpadding="0" cellspacing="0" style="width: 100%;">
		<td class="header2" style="text-align: left;">About Us</td>
		<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
	</table>
	<p>Made with love by a team of talented people from <b>BinaryStack</b>.</p>
	<p>Credits: <b><a class="link2" href="https://instagram.com/abhinavtj/">@AbhinavTJ</a></b>, <b><a class="link2" href="https://instagram.com/abishek_devendran/">@AbishekDevendran</a></b> & <b><a class="link2" href="https://instagram.com/therealsujitk">@therealsujitk</a></b>.</p>
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

<div id="role-modal" class="modal" style="text-align: left;">
	<?php
		$query = "SELECT * FROM town_" . $townID . " WHERE user_id = " . $userID . ";";
		if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_mafia"]) {
			echo '<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Your Role</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
			</table>
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td><img src="/assets/cards/mafia.png" style="height: 150px;"></img></td>
				<td><h3>Mafia</h3><br><p style="padding: 0; margin: 0;">Your job is to take over <b>' . $_SESSION["town"] . '</b> with the help of the other mafia members by killing citizens every night.</p></td>
			</table>';
		}
		else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_poser"]) {
			echo '<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Your Role</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
			</table>
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td><img src="/assets/cards/poser.png" style="height: 150px;"></img></td>
				<td><h3>Poser</h3><br><p style="padding: 0; margin: 0;">Your job is to help the' . $_SESSION["mob"] . ' mafia take over <b>' . $_SESSION["town"] . '</b> by making people think that you are a member of the mafia.</p></td>
			</table>';
		}
		else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_medic"]) {
			echo '<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Your Role</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
			</table>
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td><img src="/assets/cards/medic.png" style="height: 150px;"></img></td>
				<td><h3>Medic</h3><br><p style="padding: 0; margin: 0;">You have the ability to heal anyone you want, you can even heal yourself. Note that you <b>can not</b> heal the same more than once in a game.</p></td>
			</table>';
		}
		else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_sherrif"]) {
			echo '<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Your Role</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
			</table>
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td><img src="/assets/cards/sherrif.png" style="height: 150px;"></img></td>
				<td><h3>Sherrif</h3><br><p style="padding: 0; margin: 0;">You have the ability to find out whether a citizen is a mafia member or not. You can use this knowledge by convincing citizens to execute mafia members.</p></td>
			</table>';
		}
		else {
			echo '<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Your Role</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
			</table>
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td><img src="/assets/cards/citizen.png" style="height: 150px;"></img></td>
				<td><h3>Citizen</h3><br><p style="padding: 0; margin: 0;">Your job is to vote on who is to be executed every day, make sure you vote on mafia members or you may lose.</p></td>
			</table>';
		}
	?>
</div>

<script>
	document.getElementById("role-modal").classList.add("show-modal");
	document.getElementById("modal-background").style.display = "block";

	$('#chat-box').on('keyup', function (event) {
		if(event.keyCode == 13) {
			document.getElementById('send').click();
		}
	});

	function sendMessage(response) {
		if(response === "Success!")
			document.getElementById('chat-box').value = "";
		else {
			closeAll();
			document.getElementById('error-message').innerHTML = response;
			document.getElementById('error-modal').classList.add("show-modal");;
			document.getElementById('modal-background').style.display = "block";
		}
	}

	$('#send').on('click', function () {
		let message = document.getElementById('chat-box').value;

		$.ajax({
			type: 'POST',
			url: '/send-message.php',
			data: {
				message: message
			}
		}).then(response => sendMessage(response));
	});
	
	function submitReport(response) {
		if(response === "Success!") {
			document.getElementById('success-bug').style.display = "block";
			document.getElementById('report').value = "";
		}
		else {
			closeAll();
			document.getElementById('error-message').innerHTML = response;
			document.getElementById('error-modal').classList.add("show-modal");;
			document.getElementById('modal-background').style.display = "block";
		}
	}

	$('#submit-report').on('click', function () {
		let report = document.getElementById('report').value;

		$.ajax({
			type: 'POST',
			url: 'report-bug.php',
			data: {
				report: report
			}
		}).then(response => submitReport(response));
	});
	
	scrolled = false;
	var elem = document.getElementById("game-display");
	setInterval(function(){
		$("#game-display").load("/game.php" + " #game-display > *" );
		if(!scrolled) {
			elem.scrollTop = elem.scrollHeight;
		}
		$("#game-update").load("/game.php" + " #game-update > *" );
	}, 1000);	
	$("#game-display").on('scroll', function(){
		if(elem.scrollTop + elem.clientHeight == elem.scrollHeight)
			scrolled=false;
		else
			scrolled = true;
	});
	
	setInterval(function(){
		$("#players").load("/game.php" + " #players > *" );
	}, 10000);
	
	window.onbeforeunload = function() {
		return "Dude, are you sure about this? If you do this, there\'s no coming back.";
	}
</script>

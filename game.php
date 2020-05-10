<script>
	<?php
		include('conn.php');
		session_start();
		$townID = $_SESSION["townID"];
		$userID = $_SESSION["userID"];
		$town = $_SESSION["town"];
		$mob = $_SESSION["mob"];
		$name = $_SESSION["name"];
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
			<th id="game-index" style="height: 5px; vertical-align: center;"><h3>
				<?php
					if($_SESSION["dailyIndex"]%2 == 0) {
						if($_SESSION["dailyIndex"] == 0)
							echo 'The First Night';
						else {
							$night = $_SESSION["dailyIndex"] / 2;
							echo 'Night ' . $night;
						}
					}
					else {
						$day = $_SESSION["dailyIndex"]/2 + 0.5;
						echo 'Day ' . $day;
					}
				?>
			</h3></th>
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
							if($_SESSION["dailyIndex"]%2 == 0) {
								$query = "SELECT user_id FROM town_" . $townID . " WHERE is_mafia = 1 AND is_killed = 0 AND is_executed = 0;";
								if($result = mysqli_query($conn, $query)) {
									while($row = mysqli_fetch_assoc($result)) {
										if($row["user_id"] == $userID) {
											$query = "SELECT * FROM chat_" . $townID . ";";
											if($result = mysqli_query($conn, $query)) {
												while($row = mysqli_fetch_assoc($result)) {
													if($row["name"] == $name)
														echo '<div style="100%; text-align: right;"><div class="chat-r"><b>' . $row["name"] . ':</b> ' . $row["message"] . '</div></div>';
													else
														echo '<div style="100%; text-align: left;"><div class="chat-l"><b>' . $row["name"] . ':</b> ' . $row["message"] . '</div></div>';
												}
											}
											break;
										}
										else {
											//non-mafia citizens (dead or alive) will see a blank screen
										}
									}
								}
							}
							else {
								$query = "SELECT user_id FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0;";
								if($result = mysqli_query($conn, $query)) {
									while($row = mysqli_fetch_assoc($result)) {
										if($row["user_id"] == $userID) {
											$query = "SELECT * FROM chat_" . $townID . ";";
											if($result = mysqli_query($conn, $query)) {
												while($row = mysqli_fetch_assoc($result)) {
													if($row["name"] == $name)
														echo '<div style="100%; text-align: right;"><div class="chat-r"><b>' . $row["name"] . ':</b> ' . $row["message"] . '</div></div>';
													else
														echo '<div style="100%; text-align: left;"><div class="chat-l"><b>' . $row["name"] . ':</b> ' . $row["message"] . '</div></div>';
												}
											}
											break;
										}
										else {
											//citizens no longer alive will see a blank screen
										}
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
						$query = "SELECT * FROM town_" . $townID . ";";
						if($result = mysqli_query($conn, $query)) {
							while($row = mysqli_fetch_assoc($result)) {
								$name = $row["name"];
								if($userID == $row["user_id"])
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
	<div id="game-update" style="display: none;">
		<?php
			$query = "SELECT daily_index FROM town_details WHERE town_id = '$townID';";
			$tempIndex = mysqli_fetch_assoc(mysqli_query($conn, $query))["daily_index"];
			
			if($_SESSION["dailyIndex"] != $tempIndex) {
				if($tempIndex%2 == 0) {
					$prev = $tempIndex/2;
					$night = $tempIndex/2;
					
					$query = "SELECT COUNT(name) FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0;";
					$dayVote = mysqli_fetch_assoc(mysqli_query($conn, $query))["COUNT(name)"];
				
					$executed = '';
					
					$query = "SELECT COUNT(day_" . $prev . "), day_" . $prev . " FROM town_" . $townID . " WHERE day_" . $prev . " <> 0 GROUP BY day_" . $prev . ";";
					if($result = mysqli_query($conn, $query)) {
						while($row = mysqli_fetch_assoc($result)) {
							if($row["COUNT(day_" . $prev . ")"] >= $dayVote/2) {
								$executed = $row["day_" . $prev];
								break;
							}
						}
					}
				
					if($executed != "") {
						$query = "UPDATE town_" . $townID . " SET is_executed = 1 WHERE user_id = " . $executed . ";";
						mysqli_query($conn, $query);
						
						$query = "SELECT name, is_mafia, is_poser, is_medic, is_sherrif FROM town_" . $townID . " WHERE user_id = " . $executed . ";";
						$executed = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
						
						if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_mafia"])
							$role = 'a *Mafia* member';
						else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_poser"])
							$role = 'the *Poser*';
						else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_sherrif"])
							$role = 'the towns *Sherrif*';
						else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_medic"])
							$role = 'the towns *Medic*';
						else
							$role = 'just a *Citizen*';
					}
				
					$query = "ALTER TABLE town_" . $townID . " ADD night_" . $night . " INT(1) NOT NULL DEFAULT 0;";
					mysqli_query($conn, $query);

					$query = "ALTER TABLE town_" . $townID . " ADD medic_" . $night . " INT(1) NOT NULL DEFAULT 0;";
					mysqli_query($conn, $query);

					$next = $night + 1;
					$query = "ALTER TABLE town_" . $townID . " ADD day_" . $next . " INT(2) NOT NULL DEFAULT 0;";
					mysqli_query($conn, $query);
				
					$query = "UPDATE town_details SET game_index = 0 WHERE town_id = '$townID';";
					mysqli_query($conn, $query);
				
					$query = "SELECT COUNT(name) FROM town_" . $townID . " WHERE is_medic = 1 AND is_killed = 0 AND is_executed = 0;";
					$max = mysqli_fetch_assoc(mysqli_query($conn, $query))["COUNT(name)"] * 2 + 1;
					$query = "UPDATE town_details SET daily_max = " . $max . " WHERE town_id = '$townID';";
					mysqli_query($conn, $query);
					
					$_SESSION["dailyIndex"] = $tempIndex;
					
					$message = '<span>You are in the underworld. There is no way to contact anyone from here.</span>';
					
					$query = "SELECT user_id FROM town_" . $townID . " WHERE is_mafia = 1 AND is_killed = 0 AND is_executed = 0;";
					if($result = mysqli_query($conn, $query)) {
						while($row = mysqli_fetch_assoc($result)) {
							if($row["user_id"] == $userID) {
								if($executed != '')
									$postMessage = 'Discuss below with the other mafia members on who is to be executed, after that click the *Kill* button to choose your next victim.';
								break;
							}
						}
					}
					
					$query = "SELECT user_id FROM town_" . $townID . " WHERE is_medic = 1 AND is_killed = 0 AND is_executed = 0;";
					if($result = mysqli_query($conn, $query)) {
						while($row = mysqli_fetch_assoc($result)) {
							if($row["user_id"] == $userID) {
								if($executed != '')
									$postMessage = 'Click the *Heal* button to choose who you\'d like to heal tonight.';
								break;
							}
						}
					}
					
					$query = "SELECT user_id FROM town_" . $townID . " WHERE is_sherrif = 1 AND is_killed = 0 AND is_executed = 0;";
					if($result = mysqli_query($conn, $query)) {
						while($row = mysqli_fetch_assoc($result)) {
							if($row["user_id"] == $userID) {
								if($executed != '')
									$postMessage = 'Click the *Reveal* button to find out more about the citizens of *' .$town . '*.';
								break;
							}
						}
					}
					
					$query = "SELECT user_id FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0;";
					if($result = mysqli_query($conn, $query)) {
						while($row = mysqli_fetch_assoc($result)) {
							if($row["user_id"] == $userID) {
								if($executed != '')
									$message = '<span>Citizens of *' . $town . '*, after the execution it was discovered that *' . $executed . '* was ' . $role . '. ' . $postMessage . '</span>';
								else
									$message = '<span>Citizens of *' . $town . '*, yesterday no one was executed. ' . $postMessage . '</span>';
								break;
							}
						}
					}
					
					echo $message;
				}
				else {
					$prev = $tempIndex/2 - 0.5;
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
					
					$_SESSION["dailyIndex"] = $tempIndex;
					
					$message = '<span>You are in the underworld. There is no way to contact anyone from here.</span>';
					
					$query = "SELECT user_id FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0;";
					if($result = mysqli_query($conn, $query)) {
						while($row = mysqli_fetch_assoc($result)) {
							if($row["user_id"] == $userID) {
								if($killed != $healed) {
									$message = '<span>Citizens of *' . $town . '*, I\'m afraid I have some bad news. Last night *' . $mob . '* struck again and murdered *' . $killed . '*. If this continues, the mafia will soon take over our beloved town. Discuss with other citizens on who you think should be executed for the henious crime, after that click the *Vote* button below.</span>';
								}
								else {
									$messgae = '<span>Citizens of *' . $town . '*, Last night our medic saved the day, there were no casualities. However, this town is still not free from the mafia. Discuss with other citizens on who you think the mafia members might be, after that click the *Vote* button below.</span>';
								}
							}
						}
					}
					
					echo $message;
				}
				
			}
			else if($_SESSION["dailyIndex"] == 0) {
				$query = "SELECT user_id FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0;";
				if($result = mysqli_query($conn, $query)) {
					while($row = mysqli_fetch_assoc($result)) {
						if($row["user_id"] == $userID) {
							$message = '<span>The citizens of *' . $town . '* are sleeping. Zzz</span>';
							break;
						}
					}
				}
			
				$query = "SELECT user_id FROM town_" . $townID . " WHERE is_mafia = 1 AND is_killed = 0 AND is_executed = 0;";
				if($result = mysqli_query($conn, $query)) {
					while($row = mysqli_fetch_assoc($result)) {
						if($row["user_id"] == $userID) {
							$message = '<span>Welcome members of *' . $mob . '*, discuss below on who you would like to kill, after that click the *Kill* button to choose your first victim.</span>';
							break;
						}
					}
				}
				
				$query = "SELECT user_id FROM town_" . $townID . " WHERE is_medic = 1 AND is_killed = 0 AND is_executed = 0;";
				if($result = mysqli_query($conn, $query)) {
					while($row = mysqli_fetch_assoc($result)) {
						if($row["user_id"] == $userID) {
							$message = '<span>Hello there! You probably already know this but, you are the towns medic. Click the *Heal* button below to use your amazing abilities. :)</span>';
							break;
						}
					}
				}
				
				$query = "SELECT user_id FROM town_" . $townID . " WHERE is_sherrif = 1 AND is_killed = 0 AND is_executed = 0;";
				if($result = mysqli_query($conn, $query)) {
					while($row = mysqli_fetch_assoc($result)) {
						if($row["user_id"] == $userID) {
							$message = '<span>Hello there! You probably already know this but, you are the towns sherrif. Click the *Reveal* button below to check whether a citizen is a mafia member.</span>';
							break;
						}
					}
				}
				
				echo $message;
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
				<td><h3>Mafia</h3><br><p style="padding: 0; margin: 0;">Your job is to take over <b>' . $town . '</b> with the help of the other mafia members by killing citizens every night.</p></td>
			</table>';
		}
		else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_poser"]) {
			echo '<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Your Role</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
			</table>
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td><img src="/assets/cards/poser.png" style="height: 150px;"></img></td>
				<td><h3>Poser</h3><br><p style="padding: 0; margin: 0;">Your job is to help the' . $mob . ' mafia take over <b>' . $town . '</b> by making people think that you are a member of the mafia.</p></td>
			</table>';
		}
		else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_medic"]) {
			echo '<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Your Role</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
			</table>
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td><img src="/assets/cards/medic.png" style="height: 150px;"></img></td>
				<td><h3>Medic</h3><br><p style="padding: 0; margin: 0;">You have the ability to heal anyone you want, you can even heal yourself. Note that you <b>can not</b> heal the same person more than once in a game.</p></td>
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
	}, 500);	
	$("#game-display").on('scroll', function(){
		if(elem.scrollTop + elem.clientHeight == elem.scrollHeight)
			scrolled=false;
		else
			scrolled = true;
	});
	
	setInterval(function(){
		let x = document.getElementById('game-update');
		if(x.innerHTML != '') {
			document.getElementById('news').innerHTML = '';
			let message = x.innerHTML;
			message = message.trim();
			message = message.slice(6, -7);
			displayNews(message, 0);
			x.innerHTML = '';
		}
		else {
			$("#game-update").load("/game.php" + " #game-update > *" );
		}
		$("#game-index").load("/game.php" + " #game-index > *" );
	}, 10000);
	
	setInterval(function(){
		$("#players").load("/game.php" + " #players > *" );
	}, 10000);
	
	window.onbeforeunload = function() {
		return "Dude, are you sure about this? If you do this, there\'s no coming back.";
	}
</script>

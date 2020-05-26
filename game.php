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

	var town = <?php echo json_encode($town); ?>;
</script>
<div id="header">
	<table cellpadding="0" cellspacing="0" style="width: 100%; padding-left: 1%; padding-right: 1%;">
		<td><img src="/assets/images/logo.png" style="height: 65px;"></img></td>
			<td style="text-align: right; vertical-align: top; padding-top: 20px;">
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
						<p id="news">
							<?php
								$message = 'The citizens of <b>' . $town . '</b> are sleeping. Zzz';
			
								$query = "SELECT user_id FROM town_" . $townID . " WHERE is_mafia = 1;";
								if($result = mysqli_query($conn, $query)) {
									while($row = mysqli_fetch_assoc($result)) {
										if($row["user_id"] == $userID) {
											$query = "SELECT name FROM town_" . $townID . " WHERE is_mafia = 1;";
											$killer = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
											$message = 'Welcome members of <b>' . $mob . '</b>, discuss below on who you would like to kill, after that <b>' . $killer . '</b> has to click the <b>Kill</b> button to choose the first victim.';
											break;
										}
									}
								}
								
								$query = "SELECT user_id FROM town_" . $townID . " WHERE is_poser = 1;";
								if($result = mysqli_query($conn, $query)) {
									while($row = mysqli_fetch_assoc($result)) {
										if($row["user_id"] == $userID) {
											$query = "SELECT name FROM town_" . $townID . " WHERE is_mafia = 1;";
											$killer = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
											$message = 'Hello there! You probably already know this but, you are the poser. The mafia members are marked in red for you, you have to try and make sure they don\'t get caught.';
											break;
										}
									}
								}

								$query = "SELECT user_id FROM town_" . $townID . " WHERE is_medic = 1;";
								if($result = mysqli_query($conn, $query)) {
									while($row = mysqli_fetch_assoc($result)) {
										if($row["user_id"] == $userID) {
											$message = 'Hello there! You probably already know this but, you are the towns medic. Click the <b>Heal</b> button below to use your amazing abilities. :)';
											break;
										}
									}
								}

								$query = "SELECT user_id FROM town_" . $townID . " WHERE is_sherrif = 1;";
								if($result = mysqli_query($conn, $query)) {
									while($row = mysqli_fetch_assoc($result)) {
										if($row["user_id"] == $userID) {
											$message = 'Hello there! You probably already know this but, you are the towns sheriff. Click the <b>Reveal</b> button below to check whether a citizen is a mafia member. Remember, you only have this ability while the night lasts.';
											break;
										}
									}
								}
								
								echo $message;
							?>
						</p>
					</div>
					<div id="game-display" style="height: 35vh; overflow-y: auto;">
						<?php
							if($_SESSION["dailyIndex"]%2 == 0) {
								$query = "SELECT user_id FROM town_" . $townID . " WHERE is_mafia = 1;";
								if($result = mysqli_query($conn, $query)) {
									while($row = mysqli_fetch_assoc($result)) {
										if($row["user_id"] == $userID) {
											$query = "SELECT * FROM chat_" . $townID . ";";
											if($result = mysqli_query($conn, $query)) {
												while($row = mysqli_fetch_assoc($result)) {
													if($row["name"] == $name)
														echo '<div style="100%; text-align: right;"><div class="message" style="background: #fff; color: #000;"><b>' . $row["name"] . ':</b> ' . $row["message"] . '</div></div>';
													else
														echo '<div style="100%; text-align: left;"><div class="message" style="background: #000; color: #fff;"><b>' . $row["name"] . ':</b> ' . $row["message"] . '</div></div>';
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
								$query = "SELECT * FROM chat_" . $townID . ";";
								if($result = mysqli_query($conn, $query)) {
									while($row = mysqli_fetch_assoc($result)) {
										if($row["name"] == $name)
											echo '<div style="100%; text-align: right;"><div class="message" style="background: #fff; color: #000;"><b>' . $row["name"] . ':</b> ' . $row["message"] . '</div></div>';
										else
											echo '<div style="100%; text-align: left;"><div class="message" style="background: #000; color: #fff;"><b>' . $row["name"] . ':</b> ' . $row["message"] . '</div></div>';
									}
								}
							}
						?>
					</div>
					<table id="game-footer" style="width: 100%;">
						<td style="width: 100%;"><input id="chat-box" placeholder="Write a message..." autocomplete="off" onkeyup="enterMessage(event)"></input></td>
						<td style="padding: 0;">
							<input id="send" class="btn" type="button" style="border-radius: 10px;" onclick="sendMessage()" value="Send" <?php
								$ability = 'disabled';
							
								if($_SESSION["dailyIndex"]%2 == 0) {
									$query = "SELECT name FROM town_" . $townID . " WHERE is_mafia = 1 AND is_killed = 0 AND is_executed = 0 AND user_id = " . $userID . ";";
									if(mysqli_fetch_assoc(mysqli_query($conn, $query)))
										$ability = '';
								}
								else {
									$query = "SELECT user_id FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0 AND user_id = " . $userID . ";";
									if(mysqli_fetch_assoc(mysqli_query($conn, $query)))
										$ability = '';
								}
								
								echo $ability;
							?>></input>
						</td>
						<td>
							<input id="vote" class="btn" type="button" style="border-radius: 10px;" onclick="openVote()" value="<?php
								$value = 'Vote';
							
								if($_SESSION["dailyIndex"]%2 == 0) {
									$flag = 1;
								
									$query = "SELECT user_id FROM town_" . $townID . " WHERE is_mafia = 1 AND user_id = " . $userID . ";";
									if(mysqli_fetch_assoc(mysqli_query($conn, $query)) && $flag) {
										$value = 'Kill';
										$flag = 0;
									}
								
									$query = "SELECT user_id FROM town_" . $townID . " WHERE is_medic = 1 AND user_id = " . $userID . ";";
									if(mysqli_fetch_assoc(mysqli_query($conn, $query)) && $flag) {
										$value = 'Heal';
										$flag = 0;
									}
								
									$query = "SELECT user_id FROM town_" . $townID . " WHERE is_sherrif = 1 AND user_id = " . $userID . ";";
									if(mysqli_fetch_assoc(mysqli_query($conn, $query)) && $flag) {
										$value = 'Reveal';
										$flag = 0;
									}
								}
							
								echo $value;
							?>" <?php
								$ability = 'disabled';
							
								if($_SESSION["dailyIndex"]%2 == 0) {
									$flag = 1;
								
									$query = "SELECT name FROM town_" . $townID . " WHERE is_mafia = 1 AND is_killed = 0 AND is_executed = 0 AND user_id = " . $userID . ";";
									if(mysqli_fetch_assoc(mysqli_query($conn, $query)) && $flag) {
										$ability = '';
										$flag = 0;
									}
							
									$query = "SELECT user_id FROM town_" . $townID . " WHERE is_medic = 1 AND is_killed = 0 AND is_executed = 0 AND user_id = " . $userID . ";";
									if(mysqli_fetch_assoc(mysqli_query($conn, $query)) && $flag) {
										$ability = '';
										$flag = 0;
									}
							
									$query = "SELECT user_id FROM town_" . $townID . " WHERE is_sherrif = 1 AND is_killed = 0 AND is_executed = 0 AND user_id = " . $userID . ";";
									if(mysqli_fetch_assoc(mysqli_query($conn, $query)) && $flag) {
										$ability = '';
										$flag = 0;
									}
								}
								else {
									$query = "SELECT user_id FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0 AND user_id = " . $userID . ";";
									if(mysqli_fetch_assoc(mysqli_query($conn, $query)))
										$ability = '';
								}
								
								echo $ability;
							?>></input>
						</td>
					</table>
				</div>
			</td>
			<td id="game-content-r" style="width: 20%;">
				<div id="players">
					<?php
						$query = "SELECT user_id, name, is_mafia, is_poser, is_medic, is_sherrif, is_executed, is_killed FROM town_" . $townID . ";";
						if($result = mysqli_query($conn, $query)) {
							while($row = mysqli_fetch_assoc($result)) {
								$name = $row["name"];
								if($userID == $row["user_id"])
									$name = $name . " <b>(You)</b>";
						
								$span = '<span style="font-weight: normal;">';
						
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
								else if($row["is_mafia"]) {
									$query = "SELECT name FROM town_" . $townID . " WHERE (is_mafia = 1 OR is_poser = 1) AND user_id = " . $userID . ";";
									if(mysqli_fetch_assoc(mysqli_query($conn, $query)))
										$span = '<span style="font-weight: normal; color: #c80000">';
								}
						
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
							echo '<span style="color: #3895d3;">Sheriff: ' . mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(user_id) FROM town_" . $_SESSION["townID"] . " WHERE is_sherrif = 1;"))["COUNT(user_id)"] . '</span><br>';
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
					$query = "SELECT game_index, daily_max FROM town_details WHERE town_id = '$townID'";
					$gameIndex = mysqli_fetch_assoc(mysqli_query($conn, $query))["game_index"];
					$dailyMax = mysqli_fetch_assoc(mysqli_query($conn, $query))["daily_max"];
					
					if($gameIndex == $dailyMax) {
						$prev = $tempIndex/2;
						$night = $tempIndex/2;
						
						$query = "TRUNCATE TABLE chat_" . $townID . ";";
						mysqli_query($conn, $query);
					
						$query = "UPDATE town_details SET daily_max = 99 WHERE town_id = '$townID';";
						mysqli_query($conn, $query);
					
						$query = "SELECT COUNT(name) FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0;";
						$dayVoteMajority = mysqli_fetch_assoc(mysqli_query($conn, $query))["COUNT(name)"] / 2;
				
						$executed = '';
					
						$query = "SELECT COUNT(day_" . $prev . "), day_" . $prev . " FROM town_" . $townID . " WHERE day_" . $prev . " <> 0 GROUP BY day_" . $prev . " ORDER BY COUNT(day_" . $prev . ") DESC;";
						$row = mysqli_fetch_assoc(mysqli_query($conn, $query));
						if($row["COUNT(day_" . $prev . ")"] > $dayVoteMajority) {
							$query = "SELECT name FROM town_" . $townID . " WHERE user_id = " . $row["day_" . $prev] . ";";
							$executed = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
						}
				
						if($executed != '') {
							$query = "UPDATE town_" . $townID . " SET is_executed = 1 WHERE name = '$executed';";
							mysqli_query($conn, $query);
						
							$query = "SELECT is_mafia, is_poser, is_medic, is_sherrif FROM town_" . $townID . " WHERE name = '$executed';";
						
							if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_mafia"])
								$role = 'a <b>Mafia</b> member';
							else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_poser"])
								$role = 'the <b>Poser</b>';
							else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_sherrif"])
								$role = 'the towns <b>Sheriff</b>';
							else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_medic"])
								$role = 'the towns <b>Medic</b>';
							else
								$role = 'just a <b>Citizen</b>';
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
					}
					else {
						$prev = $tempIndex/2;
					
						$query = "SELECT COUNT(name) FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0;";
						$dayVoteMajority = mysqli_fetch_assoc(mysqli_query($conn, $query))["COUNT(name)"] / 2;
				
						$executed = '';
					
						$query = "SELECT COUNT(day_" . $prev . "), day_" . $prev . " FROM town_" . $townID . " WHERE day_" . $prev . " <> 0 GROUP BY day_" . $prev . " ORDER BY COUNT(day_" . $prev . ") DESC;";
						$row = mysqli_fetch_assoc(mysqli_query($conn, $query));
						if($row["COUNT(day_" . $prev . ")"] > $dayVoteMajority) {
							$query = "SELECT name FROM town_" . $townID . " WHERE user_id = " . $row["day_" . $prev] . ";";
							$executed = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
						}
				
						if($executed != '') {
							$query = "SELECT is_mafia, is_poser, is_medic, is_sherrif FROM town_" . $townID . " WHERE name = '$executed';";
						
							if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_mafia"])
								$role = 'a <b>Mafia</b> member';
							else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_poser"])
								$role = 'the <b>Poser</b>';
							else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_sherrif"])
								$role = 'the towns <b>Sheriff</b>';
							else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_medic"])
								$role = 'the towns <b>Medic</b>';
							else
								$role = 'just a <b>Citizen</b>';
						}
					}
					
					$_SESSION["dailyIndex"] = $tempIndex;
					
					$message = '<span>You are in the underworld. There is no way to contact anyone from here.</span>';
					$postMessage = 'The citizens of <b>' . $town . '</b> are sleeping. Zzz';
					
					$query = "SELECT user_id FROM town_" . $townID . " WHERE is_mafia = 1 AND is_killed = 0 AND is_executed = 0 AND user_id = " . $userID . ";";
					if(mysqli_fetch_assoc(mysqli_query($conn, $query))) {
						$query = "SELECT name FROM town_" . $townID . " WHERE is_mafia = 1 AND is_killed = 0 AND is_executed = 0;";
						$killer = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
						$postMessage = 'Discuss below with the other mafia members on who is to be killed, after that <b>' . $killer . '</b> has to click the <b>Kill</b> button to choose the next victim.';
					}
					
					$query = "SELECT user_id FROM town_" . $townID . " WHERE is_medic = 1 AND is_killed = 0 AND is_executed = 0 AND user_id = " . $userID . ";";
					if(mysqli_fetch_assoc(mysqli_query($conn, $query)))
						$postMessage = 'Click the <b>Heal</b> button to choose who you\'d like to heal tonight.';
					
					$query = "SELECT user_id FROM town_" . $townID . " WHERE is_sherrif = 1 AND is_killed = 0 AND is_executed = 0 AND user_id = " . $userID . ";";
					if(mysqli_fetch_assoc(mysqli_query($conn, $query)))
						$postMessage = 'Click the <b>Reveal</b> button to find out more about the citizens of <b>' . $town . '</b> before the night ends.';
					
					$query = "SELECT user_id FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0 AND user_id = " . $userID . ";";
					if(mysqli_fetch_assoc(mysqli_query($conn, $query)))
						if($executed != '')
							$message = '<span>Citizens of <b>' . $town . '</b>, after the execution it was discovered that <b>' . $executed . '</b> was ' . $role . '. ' . $postMessage . '</span>';
						else
							$message = '<span>Citizens of <b>' . $town . '</b>, yesterday no one was executed. ' . $postMessage . '</span>';
					
					$_SESSION["message"] = $message;
				}
				else {
					$query = "SELECT game_index, daily_max FROM town_details WHERE town_id = '$townID'";
					$gameIndex = mysqli_fetch_assoc(mysqli_query($conn, $query))["game_index"];
					$dailyMax = mysqli_fetch_assoc(mysqli_query($conn, $query))["daily_max"];
					
					if($gameIndex == $dailyMax) {
						$prev = $tempIndex/2 - 0.5;
						
						$query = "TRUNCATE TABLE chat_" . $townID . ";";
						mysqli_query($conn, $query);
					
						$query = "UPDATE town_details SET daily_max = 99 WHERE town_id = '$townID';";
						mysqli_query($conn, $query);
					
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
					}
					else {
						$prev = $tempIndex/2 - 0.5;
					
						$query = "SELECT name FROM town_" . $townID . " WHERE night_" . $prev . " <> 0;";
						$killed = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
						$query = "SELECT name FROM town_" . $townID . " WHERE medic_" . $prev . " <> 0;";
						$healed = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
					}
					
					$_SESSION["dailyIndex"] = $tempIndex;
					
					$message = '<span>You are in the underworld. There is no way to contact anyone from here.</span>';
					
					$query = "SELECT user_id FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0;";
					if($result = mysqli_query($conn, $query))
						while($row = mysqli_fetch_assoc($result))
							if($row["user_id"] == $userID) {
								if($killed != $healed)
									$message = '<span>Citizens of <b>' . $town . '</b>, I\'m afraid I have some bad news. Last night <b>' . $mob . '</b> struck again and murdered <b>' . $killed . '</b>. Discuss with other citizens on who you think should be executed for the henious crime, after that click the <b>Vote</b> button below.</span>';
								else
									$message = '<span>Citizens of <b>' . $town . '</b>, Last night our medic saved the day, there were no casualities. However, this town is still not free from the mafia. Discuss with other citizens on who you think the mafia members might be, after that click the <b>Vote</b> button below.</span>';
								break;
							}
					
					$_SESSION["message"] = $message;
				}
			}
			
			echo $_SESSION["message"];
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
	<p>Credits: <b><a class="link2" href="https://instagram.com/abishek_devendran/">@AbishekDevendran</a></b> & <b><a class="link2" href="https://instagram.com/therealsujitk">@therealsujitk</a></b>.</p>
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
		$query = "SELECT is_mafia, is_poser, is_medic, is_sherrif FROM town_" . $townID . " WHERE user_id = " . $userID . ";";
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
				<td><h3>Poser</h3><br><p style="padding: 0; margin: 0;">Your job is to help ' . $mob . ' take over <b>' . $town . '</b> by moving the suspicion away from the mafia. You can even make the citizens think you are a mafia member.</p></td>
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
				<td><img src="/assets/cards/sheriff.png" style="height: 150px;"></img></td>
				<td><h3>Sheriff</h3><br><p style="padding: 0; margin: 0;">You have the ability to find out whether a citizen is a mafia member or not. You can use this knowledge by convincing citizens to execute mafia members.</p></td>
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

<div id="vote-modal" class="modal">
	<?php
		if($_SESSION["dailyIndex"]%2 == 0) {
			$flag = 1;
		
			$query = "SELECT user_id FROM town_" . $townID . " WHERE is_mafia = 1 AND is_killed = 0 AND is_executed = 0 AND user_id = " . $userID . ";";
			if(mysqli_fetch_assoc(mysqli_query($conn, $query)) && $flag) {
				$query = "SELECT user_id FROM town_" . $townID . " WHERE is_mafia = 1 AND is_killed = 0 AND is_executed = 0;";
				$killer = mysqli_fetch_assoc(mysqli_query($conn, $query))["user_id"];
				
				if($killer == $userID) {
					$night = $_SESSION["dailyIndex"] / 2;
					$query = "SELECT name, avatar FROM town_" . $townID . " WHERE night_" . $night . " = 1;";
					if(!mysqli_fetch_assoc(mysqli_query($conn, $query))) {
						echo '<table cellpadding="0" cellspacing="0" style="width: 100%;">
							<td class="header2" style="text-align: left;">Choose a victim</td>
							<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
						</table>';
				
						echo '<div style="margin: 10px;"><div id="candidates">';
						$query = "SELECT user_id, name, avatar, is_mafia FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0;";
						if($result = mysqli_query($conn, $query))
							while($row = mysqli_fetch_assoc($result)) {
								$name = $row["name"];
								if($userID == $row["user_id"])
									$name = $name . ' <b>(You)</b>';
								if($row["is_mafia"])
									$color = '#c80000';
								else
									$color = '#fff';
								echo '<figure style="margin: 10px; display: inline-block;"><img class="candidate candidate-mafia" style="height: 100px;" src="'. $row["avatar"] .'" onclick="registerVote(`mafia`, `' . $row["user_id"] . '`);"></img><figcaption style="color: ' . $color . ';">' . $name . '</figcaption></figure>';
							}
						echo '</div></div>';
					}
					else {
						$name = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
						$avatar = mysqli_fetch_assoc(mysqli_query($conn, $query))["avatar"];
					
						echo '<table cellpadding="0" cellspacing="0" style="width: 100%;">
							<td class="header2" style="text-align: left;">Victim chosen</td>
							<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
						</table>
						<table cellpadding="0" cellspacing="0" style="width: 100%;">
							<td><img src="' . $avatar . '" style="height: 150px;"></img></td>
							<td><h3>' . $name . '</h3><br><p style="padding: 0; margin: 0;">You have already selected <b>' . $name . '</b> as your victim. You can only kill <b>one</b> citizen per night.</p></td>
						</table>';
					}
				}
				else {
					echo '<table cellpadding="0" cellspacing="0" style="width: 100%;">
						<td class="header2" style="text-align: left;">Choose a victim</td>
						<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
					</table>
					<table cellpadding="0" cellspacing="0" style="width: 100%;">
						<td><img src="/assets/images/warning.png" style="height: 50px;"></img></td>
						<td><p style="padding: 0; margin: 0;">Your accomplice will be taking care of the dirty work for tonight.</p></td>
					</table>';
				}
				
				$flag = 0;
			}
		
			$query = "SELECT user_id FROM town_" . $townID . " WHERE is_medic = 1 AND is_killed = 0 AND is_executed = 0 AND user_id = " . $userID . ";";
			if(mysqli_fetch_assoc(mysqli_query($conn, $query)) && $flag) {
				$night = $_SESSION["dailyIndex"] / 2;
				$query = "SELECT name, avatar FROM town_" . $townID . " WHERE medic_" . $night . " = 1;";
				if(!mysqli_fetch_assoc(mysqli_query($conn, $query))) {
					echo '<table cellpadding="0" cellspacing="0" style="width: 100%;">
						<td class="header2" style="text-align: left;">Select a citizen</td>
						<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
					</table>';
				
					echo '<div style="margin: 10px;"><div id="candidates">';
					$query = "SELECT user_id, name, avatar FROM town_" . $townID . " WHERE saved = 0 AND is_killed = 0 AND is_executed = 0;";
					if($result = mysqli_query($conn, $query))
						while($row = mysqli_fetch_assoc($result)) {
							$name = $row["name"];
							if($userID == $row["user_id"])
								$name = $name . ' <b>(You)</b>';
							echo '<figure style="margin: 10px; display: inline-block;"><img class="candidate candidate-medic" style="height: 100px;" src="'. $row["avatar"] .'" onclick="registerVote(`medic`, `' . $row["user_id"] . '`);"></img><figcaption style="color: ' . $color . ';">' . $name . '</figcaption></figure>';
						}
					echo '</div></div>';
				}
				else {
					$name = mysqli_fetch_assoc(mysqli_query($conn, $query1))["name"];
					$avatar = mysqli_fetch_assoc(mysqli_query($conn, $query1))["avatar"];
					
					echo '<table cellpadding="0" cellspacing="0" style="width: 100%;">
						<td class="header2" style="text-align: left;">Citizen saved</td>
						<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
					</table>
					<table cellpadding="0" cellspacing="0" style="width: 100%;">
						<td><img src="' . $avatar . '" style="height: 150px;"></img></td>
						<td><h3>' . $name . '</h3><br><p style="padding: 0; margin: 0;">You have already saved <b>' . $name . '</b> tonight. You can only save <b>one</b> citizen per night.</p></td>
					</table>';
				}
				
				$flag = 0;
			}

			$query = "SELECT user_id FROM town_" . $townID . " WHERE is_sherrif = 1 AND is_killed = 0 AND is_executed = 0 AND user_id = " . $userID . ";";
			if(mysqli_fetch_assoc(mysqli_query($conn, $query)) && $flag) {
				echo '<table cellpadding="0" cellspacing="0" style="width: 100%;">
					<td class="header2" style="text-align: left;">Select a citizen</td>
					<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
				</table>';
				
				echo '<div style="margin: 10px;"><div id="candidates">';
				$query = "SELECT user_id, name, avatar FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0;";
				if($result = mysqli_query($conn, $query))
					while($row = mysqli_fetch_assoc($result)) {
						$name = $row["name"];
						if($userID == $row["user_id"])
							continue;
						echo '<figure style="margin: 10px; display: inline-block;"><img class="candidate candidate-sherrif" style="height: 100px;" src="'. $row["avatar"] .'" onclick="checkMafia(`' . $row["user_id"] . '`);"></img><figcaption style="color: ' . $color . ';">' . $name . '</figcaption></figure>';
					}
				echo '</div></div>';
				
				$flag = 0;
			}
		}
		else {
			$query = "SELECT user_id FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0 AND user_id = " . $userID . ";";
			if(mysqli_fetch_assoc(mysqli_query($conn, $query))) {
				$day = $_SESSION["dailyIndex"]/2 + 0.5;
				$query = "SELECT day_" . $day . " FROM town_" . $townID . " WHERE user_id = " . $userID . " AND day_" . $day . " <> 0;";
				if(!mysqli_fetch_assoc(mysqli_query($conn, $query))) {
					echo '<table cellpadding="0" cellspacing="0" style="width: 100%;">
						<td class="header2" style="text-align: left;">Vote for execution</td>
						<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
					</table>';
				
					echo '<div style="margin: 10px;"><div id="candidates">';
					$query = "SELECT user_id, name, avatar FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0;";
					if($result = mysqli_query($conn, $query))
						while($row = mysqli_fetch_assoc($result)) {
							$name = $row["name"];
							if($userID == $row["user_id"])
								$name = $name . ' <b>(You)</b>';
							echo '<figure style="margin: 10px; display: inline-block;"><img class="candidate" style="height: 100px;" src="'. $row["avatar"] .'" onclick="registerVote(`citizen`, `' . $row["user_id"] . '`);"></img><figcaption style="color: ' . $color . ';">' . $name . '</figcaption></figure>';
						}
					echo '</div></div>';
				}
				else {
					$vote = mysqli_fetch_assoc(mysqli_query($conn, $query))["day_" . $day];
					$query = "SELECT name, avatar FROM town_" . $townID . " WHERE user_id = " . $vote . ";";
					
					$name = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
					$avatar = mysqli_fetch_assoc(mysqli_query($conn, $query))["avatar"];
					
					echo '<table cellpadding="0" cellspacing="0" style="width: 100%;">
						<td class="header2" style="text-align: left;">Your vote</td>
						<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
					</table>
					<table cellpadding="0" cellspacing="0" style="width: 100%;">
						<td><img src="' . $avatar . '" style="height: 150px;"></img></td>
						<td><h3>' . $name . '</h3><br><p style="padding: 0; margin: 0;">You have already voted for <b>' . $name . '</b> today. You can only vote for <b>one</b> citizen per day.</p></td>
					</table>';
				}
			}
		}
	?>
</div>

<div id="modal-background2"></div>

<div id="win-modal" class="modal">
	<table cellpadding="0" cellspacing="0" style="width: 100%;">
		<td class="header2" style="text-align: center;">Game Over</td>
	</table>
	<table cellpadding="0" cellspacing="0" style="width: 100%;">
		<td id="results" style="padding: 0; padding-bottom: 10px;"><p>
			<?php
				$query = "SELECT COUNT(user_id) FROM town_" . $townID . " WHERE is_mafia = 1 AND is_killed = 0 AND is_executed = 0";
				$mafiaPopulation = mysqli_fetch_assoc(mysqli_query($conn, $query))["COUNT(user_id)"];
				$query = "SELECT COUNT(user_id) FROM town_" . $townID . " WHERE is_mafia = 0 AND is_killed = 0 AND is_executed = 0";
				$nonMafiaPopulation = mysqli_fetch_assoc(mysqli_query($conn, $query))["COUNT(user_id)"];
			
				if($mafiaPopulation == 0) {
					echo 'Citizens Win! Our town is free from the members of <b>' . $mob . '</b>. All of them are dead.';
					echo '<input style="margin-top: 10px; margin-left: 50%; transform: translate(-50%, 0%);" class="btn" type="button" value="Go Home" onclick="goHome()">';
				}
				else if(($mafiaPopulation == $nonMafiaPopulation)) {
					echo 'Mafia Wins! <b>' . $mob . '</b> has taken over our town. The game ends here because after the mafia kills one of the citizens tonight, there will never be a majority on who is to be executed.';
					echo '<input style="margin-top: 10px; margin-left: 50%; transform: translate(-50%, 0%);" class="btn" type="button" value="Go Home" onclick="goHome()">';
				}
			?>
		</p></td>
	</table>
</div>

<script>
	document.getElementById("role-modal").classList.add("show-modal");
	document.getElementById("modal-background").style.display = "block";
	
	let gameIndex = document.getElementById('game-index').innerHTML.slice(4, -5).trim();
	document.getElementsByTagName('title')[0].innerHTML = gameIndex + ' • ' + town + ' - Mafia';

	function sendMsg(response, message) {
		if(response === "Success!")
			document.getElementById('chat-box').value = "";
		else {
			closeAll();
			document.getElementById('error-message').innerHTML = response;
			document.getElementById('error-modal').classList.add("show-modal");;
			document.getElementById('modal-background').style.display = "block";
			
			document.getElementById('chat-box').value = message;
		}
	}

	function sendMessage() {
		var message = document.getElementById('chat-box').value;
		document.getElementById('chat-box').value = "";

		$.ajax({
			type: 'POST',
			url: '/send-message.php',
			data: {
				message: message
			}
		}).then(response => sendMsg(response, message));
	}
	
	function enterMessage(event) {
		let sendAbility = document.getElementById('send').disabled;
		if(event.keyCode == 13 && !sendAbility) {
			sendMessage();
		}
	}
	
	function regVote(response) {
		if(response === "Success!") {
			$("#vote-modal").load("/game.php" + " #vote-modal > *" );
		}
		else {
			closeAll();
			document.getElementById('error-message').innerHTML = response;
			document.getElementById('error-modal').classList.add("show-modal");;
			document.getElementById('modal-background').style.display = "block";
		}
	}
	
	function registerVote(role, vote) {
		closeAll();
		$.ajax({
			type: 'POST',
			url: '/register-vote.php',
			data: {
				role: role,
				vote: vote
			}
		}).then(response => regVote(response));
	}
	
	function chkMafia(response) {
		document.getElementById('vote-modal').innerHTML = response;
	}
	
	function checkMafia(selected) {
		$.ajax({
			type: 'POST',
			url: '/check-mafia.php',
			data: {
				selected: selected
			}
		}).then(response => chkMafia(response));
	}
	
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
	
	function clearSession(response) {
		if(response === "Success!") {
			window.location.href = window.location.href;
		}
		else {
			$('#home').prop('disabled', false);
			$('#home').val("Go Home");
		}
	}

	function goHome() {
		$('#home').prop('disabled', true);
		$('#home').val('Please wait...');
	
		$.ajax({
			type: 'POST',
			url: 'leave-game.php',
			data: {
				check: 'true'
			}
		}).then(response => clearSession(response));
	}
	
	scrolled = false;
	var elem = document.getElementById("game-display");
	var chat = setInterval(function(){
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
	
	var game = setInterval(function(){
		$("#game-update").load("/game.php" + " #game-update > *" );
		
		var message = document.getElementById('game-update').innerHTML.trim();
		message = message.slice(6, -7);
		var news = document.getElementById('news');
		
		if(message != news.innerHTML && message != '') {
			news.innerHTML = message;
			closeAll();
			$("#game-index").load("/game.php" + " #game-index > *" );
			$("#game-footer").load("/game.php" + " #game-footer > *" );
			$("#vote-modal").load("/game.php" + " #vote-modal > *" );
			$("#players").load("/game.php" + " #players > *" );
			$("#results").load("/game.php" + " #results > *" );
		}
		
		let gameIndex = document.getElementById('game-index').innerHTML.slice(4, -5).trim();
		document.getElementsByTagName('title')[0].innerHTML = gameIndex + ' • ' + town + ' - Mafia';
		
		var results = document.getElementById('results').innerHTML;
		results = results.slice(3, -4).trim();
	
		if(results != '') {
			closeAll();
			document.getElementById('win-modal').classList.add("show-modal");
			document.getElementById('modal-background2').style.display = "block";
			clearInterval(game);
			clearInterval(chat);
		}
	}, 1000);
	
	window.onbeforeunload = function() {
		return "Are you sure you want to leave? If your session ends, you won\'t be able to continue playing this game.";
	}
</script>

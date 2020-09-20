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
	var townID = <?php echo json_encode($townID); ?>;
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

<div id="notification" class="alert"></div>

<div style="display: none;">
<?php
$query = "SELECT daily_index FROM town_details WHERE town_id = '$townID';";
$tempIndex = mysqli_fetch_assoc(mysqli_query($conn, $query))["daily_index"];

if($_SESSION["dailyIndex"] != $tempIndex) {
	if($tempIndex%2 == 0) {
		$_SESSION["dailyIndex"] = $tempIndex;
		$_SESSION["revealed"] = '';

		$prev = $tempIndex/2;

		$query = "SELECT COUNT(name) FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0;";
		$dayVoteMajority = mysqli_fetch_assoc(mysqli_query($conn, $query))["COUNT(name)"] / 2;

		$executed = '';
	
		$query = "SELECT COUNT(day_" . $prev . "), day_" . $prev . " FROM town_" . $townID . " WHERE day_" . $prev . " <> 0 GROUP BY day_" . $prev . " ORDER BY COUNT(day_" . $prev . ") DESC;";
		$row = mysqli_fetch_assoc(mysqli_query($conn, $query));
		if($row["COUNT(day_" . $prev . ")"] > $dayVoteMajority) {
			$query = "SELECT name FROM town_" . $townID . " WHERE user_id = " . $row["day_" . $prev] . ";";
			$executed = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];

			$query = "SELECT is_mafia, is_poser, is_medic, is_sherrif FROM town_" . $townID . " WHERE name = '$executed';";
		
			if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_mafia"])
				$role = 'a *Mafia* member';
			else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_poser"])
				$role = 'the *Poser*';
			else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_sherrif"])
				$role = 'the towns *Sheriff*';
			else if(mysqli_fetch_assoc(mysqli_query($conn, $query))["is_medic"])
				$role = 'the towns *Medic*';
			else
				$role = 'just a *Citizen*';
		}
		
		$message = '<p id="news-update">You are in the underworld. There is no way to contact anyone from here. Maybe make a deal with Lucifer?</p>';
		$postMessage = 'The citizens of *' . $town . '* are sleeping. Zzz</p>';
		
		$flag = 1;
		
		$query = "SELECT user_id FROM town_" . $townID . " WHERE is_mafia = 1 AND is_killed = 0 AND is_executed = 0 AND user_id = " . $userID . ";";
		if(mysqli_fetch_assoc(mysqli_query($conn, $query)) && $flag) {
			$query = "SELECT name FROM town_" . $townID . " WHERE is_mafia = 1 AND is_killed = 0 AND is_executed = 0;";
			$killer = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
			$postMessage = 'Discuss below with the other mafia members on who is to be killed, after that *' . $killer . '* has to click the *Kill* button to choose the next victim.</p>';
			$flag = 0;
		}
		
		$query = "SELECT user_id FROM town_" . $townID . " WHERE is_medic = 1 AND is_killed = 0 AND is_executed = 0 AND user_id = " . $userID . ";";
		if(mysqli_fetch_assoc(mysqli_query($conn, $query)) && $flag) {
			$postMessage = 'Click the *Heal* button to choose who you\'d like to heal tonight.</p>';
			$flag = 0;
		}
		
		$query = "SELECT user_id FROM town_" . $townID . " WHERE is_sherrif = 1 AND is_killed = 0 AND is_executed = 0 AND user_id = " . $userID . ";";
		if(mysqli_fetch_assoc(mysqli_query($conn, $query)) && $flag) {
			$postMessage = 'Click the *Reveal* button to find out more about the citizens of *' . $town . '* before the night ends.</p>';
			$flag = 0;
		}
		
		$query = "SELECT user_id FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0 AND user_id = " . $userID . ";";
		if(mysqli_fetch_assoc(mysqli_query($conn, $query)))
			if($executed != '')
				$message = '<p id="news-update">Citizens of *' . $town . '*, after the execution it was discovered that *' . $executed . '* was ' . $role . '. ' . $postMessage;
			else
				$message = '<p id="news-update">Citizens of *' . $town . '*, yesterday no one was executed. ' . $postMessage;
		
		$message .= "<script>
			indexTimeout = setTimeout(function() {
				$.ajax({
					type: 'POST',
					url: '/resources/register-vote.php',
					data: {
						role: 'timeup',
						vote: ''
					},
					error: function() {
						let message = 'Sorry, we are having some trouble communicating with our servers. Please check your internet connection.';
						openError(message);
					}
				});
			}, 60000);
		</script>";

		$_SESSION["message"] = $message;
	}
	else {
		$_SESSION["dailyIndex"] = $tempIndex;

		$prev = $tempIndex/2 - 0.5;
		
		$query = "SELECT name FROM town_" . $townID . " WHERE night_" . $prev . " <> 0;";
		$killed = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
		$query = "SELECT name FROM town_" . $townID . " WHERE medic_" . $prev . " <> 0;";
		$healed = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
		
		$message = '<p id="news-update">You are in the underworld. There is no way to contact anyone from here. Maybe make a deal with Lucifer?</p>';
		
		$query = "SELECT name FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0 AND user_id = " . $userID . ";";
		if(mysqli_fetch_assoc(mysqli_query($conn, $query))) {
			if($killed != $healed)
				$message = '<p id="news-update">Citizens of *' . $town . '*, I\'m afraid I have some bad news. Last night *' . $mob . '* struck again and murdered *' . $killed . '*. Discuss with other citizens on who you think should be executed for the henious crime, after that click the *Vote* button below.</p>';
			else
				$message = '<p id="news-update">Citizens of *' . $town . '*, Last night our medic saved the day, there were no casualities. However, this town is still not free from the mafia. Discuss with other citizens on who you think the mafia members might be, after that click the *Vote* button below.</p>';
		}

		$message .= "<script>
			indexTimeout = setTimeout(function() {
				$.ajax({
					type: 'POST',
					url: '/resources/register-vote.php',
					data: {
						role: 'timeup',
						vote: ''
					},
					error: function() {
						let message = 'Sorry, we are having some trouble communicating with our servers. Please check your internet connection.';
						openError(message);
					}
				});
			}, 150000);
		</script>";
		
		$_SESSION["message"] = $message;
	}
}

echo $_SESSION["message"];

if($_SESSION["dailyIndex"] == 0) {
	$message = '<p id="news-update">The citizens of *' . $town . '* are sleeping. Zzz</p>';
	$flag = 1;

	$query = "SELECT user_id FROM town_" . $townID . " WHERE is_mafia = 1 AND user_id = " . $userID . ";";
	if(mysqli_fetch_assoc(mysqli_query($conn, $query)) && $flag) {
		$query = "SELECT name FROM town_" . $townID . " WHERE is_mafia = 1;";
		$killer = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
		$message = '<p id="news-update">Welcome members of *' . $mob . '*, discuss below on who you would like to kill, after that *' . $killer . '* has to click the *Kill* button to choose the first victim.</p>';
		$query = "SELECT medic_0 FROM town_" . $townID . " WHERE night_0 <> 0;";
		if($name == $killer && !mysqli_fetch_assoc(mysqli_query($conn, $query)))
			$message = $message . '<script>
				let notification = document.getElementById("notification");
				notification.innerHTML = "We are waiting for you to choose your victim.";
				notification.classList.remove("hide-alert");
				notification.classList.add("show-alert");
			</script>';
		$flag = 0;
	}

	$query = "SELECT user_id FROM town_" . $townID . " WHERE is_poser = 1 AND user_id = " . $userID . ";";
	if(mysqli_fetch_assoc(mysqli_query($conn, $query)) && $flag) {
		$message = '<p id="news-update">Hello there! You probably already know this but, you are the poser. The mafia members are marked in red for you, you have to try and make sure they don\'t get caught.</p>';
		$flag = 0;
	}

	$query = "SELECT user_id FROM town_" . $townID . " WHERE is_medic = 1 AND user_id = " . $userID . ";";
	if(mysqli_fetch_assoc(mysqli_query($conn, $query)) && $flag) {
		$message = '<p id="news-update">Hello there! You probably already know this but, you are the towns medic. Click the *Heal* button below to use your amazing abilities. :)</p>';
		$query = "SELECT medic_0 FROM town_" . $townID . " WHERE medic_0 <> 0;";
		if(!mysqli_fetch_assoc(mysqli_query($conn, $query)))
			$message = $message . '<script>
				let notification = document.getElementById("notification");
				notification.innerHTML = "We are waiting for you to save a citizen.";
				notification.classList.remove("hide-alert");
				notification.classList.add("show-alert");
			</script>';
		$flag = 0;
	}

	$query = "SELECT user_id FROM town_" . $townID . " WHERE is_sherrif = 1 AND user_id = " . $userID . ";";
	if(mysqli_fetch_assoc(mysqli_query($conn, $query)) && $flag) {
		$message = '<p id="news-update">Hello there! You probably already know this but, you are the towns sheriff. Click the *Reveal* button below to check whether a citizen is a mafia member. Remember, you only have this ability while the night lasts.</p>';
		if($_SESSION["revealed"] == '')
			$message = $message . '<script>
				let notification = document.getElementById("notification");
				notification.innerHTML = "Hurry up! You\'re running out of time!!";
				notification.classList.remove("hide-alert");
				notification.classList.add("show-alert");
			</script>';
		$flag = 0;
	}

	$message .= "<script>
		indexTimeout = setTimeout(function() {
			$.ajax({
				type: 'POST',
				url: '/resources/register-vote.php',
				data: {
					role: 'timeup',
					vote: ''
				},
				error: function() {
					let message = 'Sorry, we are having some trouble communicating with our servers. Please check your internet connection.';
					openError(message);
				}
			});
		}, 60000);
	</script>";

	echo $message;
}
?>
</div>

<div id="header-mobile">
	<i id="menu-mobile" class="fas fa-bars" onclick="openMenu();"></i>
	<div id="logo-mobile"><h3>
		<?php
		if($_SESSION["dailyIndex"] == 0) {
			echo 'The First Night';
		}
		else if($_SESSION["dailyIndex"]%2 == 0) {
			$night = $_SESSION["dailyIndex"] / 2;
			echo 'Night ' . $night;
		}
		else {
			$day = $_SESSION["dailyIndex"]/2 + 0.5;
			echo 'Day ' . $day;
		}
		?>
	</h3></div>
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
	</nav>
</div>

<div id="game">
	<table id="game-content">
		<tr>
			<th id="game-index" style="vertical-align: center;"><h3>
			<?php
			if($_SESSION["dailyIndex"] == 0) {
				echo 'The First Night';
			}
			else if($_SESSION["dailyIndex"]%2 == 0) {
				$night = $_SESSION["dailyIndex"] / 2;
				echo 'Night ' . $night;
			}
			else {
				$day = $_SESSION["dailyIndex"]/2 + 0.5;
				echo 'Day ' . $day;
			}
			?>
			</h3></th>
			<th id="role-button" style="vertical-align: center;"><input id="my-role" class="btn" type="button" value="My Role" onclick="openRole()"></input></th>
		</tr>
		<tr id="mobile-game-nav">
			<td colspan="2"><table id="mobile-game-nav-content" style="width: 100%; vertical-align: middle;">
				<th><i id="button-l" class="fas fa-comment-alt fa-2x mobile-game-nav" onclick="loadL();" style="color: #c80000;"></i></th>
				<th><i id="button-r" class="fas fa-info-circle fa-2x mobile-game-nav" onclick="loadR();" style="color: #936c6c;"></i></th>
			</table></td>
		</tr>
		<tr>
			<td id="game-content-l" style="width: 80%; padding: 0;">
				<div id="content">
					<div id="news-bar">
						<p id="news">News from <b><?php echo $_SESSION["town"] ?></b> will show up here.</p>
					</div>
					<div id="game-display">
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
													echo '<div style="100%; text-align: right;"><div class="message" style="background: #fff; color: #000;">' . $row["message"] . '</div></div>';
												else
													echo '<div style="100%; text-align: left;"><div class="message" style="background: #000; color: #fff;"><b>' . $row["name"] . ':</b> ' . $row["message"] . '</div></div>';
											}
										}
										break;
									}
								}
							}
						}
						else {
							$query = "SELECT * FROM chat_" . $townID . ";";
							if($result = mysqli_query($conn, $query)) {
								while($row = mysqli_fetch_assoc($result)) {
									if($row["name"] == $name)
										echo '<div style="100%; text-align: right;"><div class="message" style="background: #fff; color: #000;"><b>' . $row["message"] . '</div></div>';
									else
										echo '<div style="100%; text-align: left;"><div class="message" style="background: #000; color: #fff;"><b>' . $row["name"] . ':</b> ' . $row["message"] . '</div></div>';
								}
							}
						}
						?>
					</div>
					<table id="game-footer" style="width: 100%;">
						<td style="width: 100%;"><input id="chat-box" placeholder="Write a message..." autocomplete="off" onkeyup="enterMessage(event)"></input></td>
						<td id="send" style="padding: 0;">
							<input class="btn" type="button" style="border-radius: 10px;" onclick="sendMessage()" value="Send" <?php
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
						<td id="vote">
							<input class="btn" type="button" style="border-radius: 10px;" onclick="openVote()" value="<?php
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
				<div id="role-mobile">
					<hr class="divider" style="width: 40%; margin-top: 0; margin-bottom: 10px;">
					<input class="btn" type="button" value="My Role" style="margin-left: 50%; transform: translate(-50%, 0%);" onclick="openRole()"></input>
					<hr class="divider" style="width: 40%; margin-top: 10px;">
				</div>
				<div id="game-details">
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
							echo '<hr class="divider">';
							echo '<span>Population: ' . mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(user_id) FROM town_" . $_SESSION["townID"] . ";"))["COUNT(user_id)"] . '</span><br>';
							echo '<span style="color: #c80000;">Mafia: ' . mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(user_id) FROM town_" . $_SESSION["townID"] . " WHERE is_mafia = 1;"))["COUNT(user_id)"] . '</span><br>';
							echo '<span style="color: #ffd300;">Poser: ' . mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(user_id) FROM town_" . $_SESSION["townID"] . " WHERE is_poser = 1;"))["COUNT(user_id)"] . '</span><br>';
							echo '<span style="color: #fa691d;">Medic: ' . mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(user_id) FROM town_" . $_SESSION["townID"] . " WHERE is_medic = 1;"))["COUNT(user_id)"] . '</span><br>';
							echo '<span style="color: #3895d3;">Sheriff: ' . mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(user_id) FROM town_" . $_SESSION["townID"] . " WHERE is_sherrif = 1;"))["COUNT(user_id)"] . '</span><br>';
						}
						?>
					</div>
				</div>
			</td>
		</td>
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
	<p>Credits: <b><a class="link2" href="https://instagram.com/abishek.stuff/" target="_blank">@AbishekDevendran</a></b> & <b><a class="link2" href="https://therealsuji.tk" target="_blank">@therealsujitk</a></b>.</p>
	<div id="version"><span>v3.0.0</span></div>
</div>

<div id="error-modal" class="modal">
	<table cellpadding="0" cellspacing="0" style="width: 100%;">
		<td class="header2" style="text-align: left;">Error!</td>
		<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
	</table>
	<table cellpadding="0" cellspacing="0" style="width: 100%;">
		<td style="width: 50px;"><img src="/assets/images/error.png" style="height: 50px;"></img></td>
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
			
					echo '<div id="candidates-box"><div id="candidates">';
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
							echo '<figure style="margin: 10px 20px 10px 0px; display: inline-block;"><img class="candidate candidate-mafia" style="height: 100px;" src="'. $row["avatar"] .'" onclick="registerVote(`mafia`, `' . $row["user_id"] . '`);"></img><figcaption style="color: ' . $color . ';">' . $name . '</figcaption></figure>';
						}
					echo '</div></div>';
					
					echo '<span id="alert" style="display: none;">We are waiting for you to choose your victim.</span>';
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
			
				echo '<div id="candidates-box"><div id="candidates">';
				$prev = $_SESSION["dailyIndex"]/2 - 1;
				if($prev < 0)
					$prev = 0;
				$query = "SELECT user_id, name, avatar FROM town_" . $townID . " WHERE medic_" . $prev . " = 0 AND is_killed = 0 AND is_executed = 0;";
				if($result = mysqli_query($conn, $query))
					while($row = mysqli_fetch_assoc($result)) {
						$name = $row["name"];
						if($userID == $row["user_id"])
							$name = $name . ' <b>(You)</b>';
						echo '<figure style="margin: 10px 20px 10px 0px; display: inline-block;"><img class="candidate candidate-medic" style="height: 100px;" src="'. $row["avatar"] .'" onclick="registerVote(`medic`, `' . $row["user_id"] . '`);"></img><figcaption>' . $name . '</figcaption></figure>';
					}
				echo '</div></div>';
				
				echo '<span id="alert" style="display: none;">We are waiting for you to save a citizen.</span>';
			}
			else {
				$name = mysqli_fetch_assoc(mysqli_query($conn, $query))["name"];
				$avatar = mysqli_fetch_assoc(mysqli_query($conn, $query))["avatar"];
				
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
			if($_SESSION["revealed"] == '') {
				echo '<table cellpadding="0" cellspacing="0" style="width: 100%;">
					<td class="header2" style="text-align: left;">Select a citizen</td>
					<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
				</table>';
		
				echo '<div id="candidates-box"><div id="candidates">';
				$query = "SELECT user_id, name, avatar FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0;";
				if($result = mysqli_query($conn, $query))
					while($row = mysqli_fetch_assoc($result)) {
						$name = $row["name"];
						if($userID == $row["user_id"])
							continue;
						echo '<figure style="margin: 10px 20px 10px 0px; display: inline-block;"><img class="candidate candidate-sherrif" style="height: 100px;" src="'. $row["avatar"] .'" onclick="registerVote(`sheriff`, `' . $row["user_id"] . '`);"></img><figcaption>' . $name . '</figcaption></figure>';
					}
				echo '</div></div>';
				
				echo '<span id="alert" style="display: none;">Hurry up! You\'re running out of time!!</span>';
			}
			else {
				echo $_SESSION["revealed"];
			}
			
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
			
				echo '<div id="candidates-box"><div id="candidates">';
				$query = "SELECT user_id, name, avatar, is_mafia FROM town_" . $townID . " WHERE is_killed = 0 AND is_executed = 0;";
				if($result = mysqli_query($conn, $query))
					while($row = mysqli_fetch_assoc($result)) {
						$name = $row["name"];
						if($userID == $row["user_id"])
							$name = $name . ' <b>(You)</b>';
						$query2 = "SELECT user_id FROM town_" . $townID . " WHERE user_id = " . $userID . " AND is_mafia = 1;";
						$color = "#fff";
						if(mysqli_fetch_assoc(mysqli_query($conn, $query2)))
							if($row["is_mafia"])
								$color = "#c80000";
						echo '<figure style="margin: 10px 20px 10px 0px; display: inline-block;"><img class="candidate" style="height: 100px;" src="'. $row["avatar"] .'" onclick="registerVote(`citizen`, `' . $row["user_id"] . '`);"></img><figcaption style="color: ' . $color . ';">' . $name . '</figcaption></figure>';
					}
				echo '</div></div>';
				
				echo '<span id="alert" style="display: none;">We are awaiting your vote.</span>';
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
		<td id="results-header" class="header2" style="text-align: center;">Game Over</td>
	</table>
	<table cellpadding="0" cellspacing="0" style="width: 100%;">
		<td id="results" style="text-align: center; padding: 0; padding-bottom: 10px;"><p>
			<?php
			$query = "SELECT COUNT(user_id) FROM town_" . $townID . " WHERE is_mafia = 1 AND is_killed = 0 AND is_executed = 0";
			$mafiaPopulation = mysqli_fetch_assoc(mysqli_query($conn, $query))["COUNT(user_id)"];
			$query = "SELECT COUNT(user_id) FROM town_" . $townID . " WHERE is_mafia = 0 AND is_killed = 0 AND is_executed = 0";
			$nonMafiaPopulation = mysqli_fetch_assoc(mysqli_query($conn, $query))["COUNT(user_id)"];
		
			$flag = 0;
			if($mafiaPopulation == 0) {
				echo 'Our town is free from the members of <b>' . $mob . '</b>. All of them are dead.';
				echo '<script>document.getElementById("results-header").innerHTML = "Citizens Wins!"</script>';
				$flag = 1;
			}
			else if(($mafiaPopulation == $nonMafiaPopulation)) {
				echo '<b>' . $mob . '</b> has taken over our town. The Mafia now has the majority members.';
				echo '<script>document.getElementById("results-header").innerHTML = "Mafia Wins!"</script>';
				$flag = 1;
			}

			if($flag) {
				echo '</br><input id="restart-game" class="btn" style="margin-top: 10px;" type="button" value="Back to Lobby" onclick="restartGame();"></input>';
				echo "<script>
					if(indexTimeout) {
						clearTimeout(indexTimeout);
						indexTimeout = null;
					}
				</script>";
			}
			?>
		</p></td>
	</table>
</div>

<div id="has-started" style="display: none;">
	<?php
		$query = "SELECT has_started FROM town_details WHERE town_id = '$townID';";
		if(mysqli_fetch_assoc(mysqli_query($conn, $query))["has_started"] == '0')
			echo "<script>
				$('body').load('lobby.php', function(response, status) {
					if(status !=  'success') {
						closeAll();
						setTimeout(function() {
							document.getElementById('error-message').innerHTML = 'Sorry, we are having some trouble communicating with our servers. Please try refreshing this page.';
							document.getElementById('error-modal').classList.add('show-modal');
							document.getElementById('modal-background').style.display = 'block';
						}, 500);
					}
				});
			</script>";
		
		mysqli_close($conn);
	?>
</div>

<script>
	gameSpaceCalc();
	clientUpdate();
    
    window.addEventListener('resize', () => {
    	gameSpaceCalc();
    });

	<?php
	if(!$_SESSION["dailyIndex"]) {
		echo "
			document.getElementById('role-modal').classList.add('show-modal');
			document.getElementById('modal-background').style.display = 'block';
		";
	}
	?>
	
	document.getElementsByTagName('title')[0].innerHTML = document.getElementById('game-index').innerHTML.slice(4, -5).trim() + ' • ' + town + ' - Mafia';
	
	wamp.topic(townID).subscribe((arr) => {
		if(arr._args[0] === "new message") {
			$("#game-display").load("game.php #game-display > *", function(response, status) {
				if(status !=  "success") {
					closeAll();
					setTimeout(function() {
						document.getElementById('error-message').innerHTML = 'Sorry, we are having some trouble communicating with our servers. Please try refreshing this page.';
						document.getElementById('error-modal').classList.add("show-modal");
						document.getElementById('modal-background').style.display = "block";
					}, 500);
				}
				else {
					$("#game-display").animate({ scrollTop: $('#game-display').prop("scrollHeight")}, 500);
				}
			});
		}
		else if(arr._args[0] === "update index") {
			if(indexTimeout) {
				clearTimeout(indexTimeout);
				indexTimeout = null;
			}
			var messageValue = document.getElementById('chat-box').value;
			closeAll();
			$("body").load("game.php", function(response, status) {
				if(status !=  "success") {
					closeAll();
					setTimeout(function() {
						document.getElementById('error-message').innerHTML = 'Sorry, we are having some trouble communicating with our servers. Please try refreshing this page.';
						document.getElementById('error-modal').classList.add("show-modal");
						document.getElementById('modal-background').style.display = "block";
					}, 500);
				}
				else {
					document.getElementById('chat-box').value = messageValue;
				}
			});
		}
		else if(arr._args[0] === "restart game") {
			$("body").load("lobby.php", function(response, status) {
				if(status !=  "success") {
					closeAll();
					setTimeout(function() {
						document.getElementById('error-message').innerHTML = 'Sorry, we are having some trouble communicating with our servers. Please try refreshing this page.';
						document.getElementById('error-modal').classList.add("show-modal");
						document.getElementById('modal-background').style.display = "block";
					}, 500);
				}
			});
		}
		else if(arr._args[0] === "try restart") {
			if(document.getElementById('restart-game')) {
				var restart = document.getElementById('restart-game');
				restart.disabled = true;
				restart.value = "Please wait...";

				restartTimeout = setTimeout(function() {
					restart.disabled = false;
					restart.value = "Back to Lobby";

					let message = 'Sorry, timeout error. Please try refreshing this page.';
					openError(message);
				}, 30000);
			}
		}
		else if(arr._args[0] === "restart failed") {
			if(document.getElementById('restart-game')) {
				var restart = document.getElementById('restart-game');
				restart.disabled = false;
				restart.value = "Back to Lobby";
				if(restartTimeout) {
					clearTimeout(restartTimeout);
					restartTimeout = null;
				}
			}
		}
	});

	window.onbeforeunload = function() {
		return "Are you sure you want to leave? If your session ends, you won\'t be able to continue playing this game.";
	}
</script>
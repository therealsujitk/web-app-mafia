<script>
	<?php
		include('conn.php');
		session_start();
		$townID = $_SESSION["townID"];
		$town = $_SESSION["town"];
	?>

	var townID = <?php echo json_encode($townID); ?>;
	var town = <?php echo json_encode($town); ?>;
	document.getElementsByTagName('title')[0].innerHTML = town + ' - Mafia';
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

<div id="town-players">
	<h2><?php echo $town; ?></h2>
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

	<p id="share">Your Town ID is <b><?php echo $townID; ?></b>. <span class="link3" onclick="copyInvite(townID);">Click here</span> to copy.</p>
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
				$("body").load("/game.php");
			},
			error: function () {
				//do something
			}
		});
	});

	$('#submit-report').on('click', function () {
		let report = document.getElementById('report').value;

		$.ajax({
			type: 'POST',
			url: 'report-bug.php',
			data: {
				report: report
			},
			success: function () {
				document.getElementById('success-bug').style.display = "block";
			},
			error: function () {
				document.getElementById('error-bug').style.display = "block";
			}
		});
	});
</script>

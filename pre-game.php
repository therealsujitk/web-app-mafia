<script>
	<?php
		include('conn.php');
		session_start();
		$userID = $_SESSION["userID"];
		$townID = $_SESSION["townID"];
		$town = $_SESSION["town"];
	?>

	var userID = <?php echo json_encode($userID); ?>;
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
			$query = "SELECT * FROM town_" . $townID . ";";
		
			if($result = mysqli_query($conn, $query))
				while($row = mysqli_fetch_assoc($result)) {
					$name = $row["name"];
					if($userID == $row["user_id"])
						$name = $name . " <b>(You)</b>";
					echo '<td style="vertical-align: top; padding: 0;"><figure style="margin: 10px;"><img style="height: 150px;" src="'. $row["avatar"] .'"></img><figcaption>' . $name . '</figcaption></figure></td>';
				}
		?>
	</table>

	<p id="share">Your Town ID is <b><?php echo $townID; ?></b>. <span class="link3" onclick="copyInvite(townID);">Click here</span> to copy.</p>
	<?php
		$query = "SELECT owner_id FROM town_details WHERE town_id = '$townID';";
		$ownerID = mysqli_fetch_assoc(mysqli_query($conn, $query))["owner_id"];
		
		if($ownerID === $userID)
			echo '<input id="start" class="btn" type="button" value="Start Game"></input>';
	?>
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
	<p>Built By: <b><a class="link2" href="https://instagram.com/abhinavtj/">@AbhinavTJ</a></b>, <b><a class="link2" href="https://instagram.com/abishek_devendran/">@AbishekDevendran</a></b> & <b><a class="link2" href="https://instagram.com/therealsujitk">@therealsujitk</a></b>.</p>
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
	function buildTown(response) {
		if(response === "Success!")
			$("body").load("/game.php");
		else {
			closeAll();
			document.getElementById('error-message').innerHTML = response;
			document.getElementById('error-modal').classList.add("show-modal");;
			document.getElementById('modal-background').style.display = "block";
		}
	}
	
	$('#start').on('click', function (event) {
		$.ajax({
			type: 'POST',
			url: '/build-town.php'
		}).then(response => buildTown(response));
	});

	setInterval(function(){
		$("#player-cards").load("/pre-game.php" + " #player-cards > *" );
	}, 1000);

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
</script>

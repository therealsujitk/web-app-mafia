<script>
	<?php
		include('conn.php');
		session_start();
	
		$userID = $_SESSION["userID"];
		$townID = $_SESSION["townID"];
		$town = $_SESSION["town"];
	?>

	var townID = <?php echo json_encode($townID); ?>;
	var town = <?php echo json_encode($town); ?>;
	document.getElementsByTagName('title')[0].innerHTML = town + ' - Mafia';
</script>
<div id="header">
	<table cellpadding="0" cellspacing="0" style="width: 100%; padding-left: 1%; padding-right: 1%;">
		<td><img src="/assets/images/logo.png" style="height: 65px;"></img></td>
			<td style="text-align: right; vertical-align: top; padding-top: 20px;">
				<nav>
				<input class="header link" style="padding-left: 10px; padding-right: 10px;" type="button" value="Privacy Policy" onclick="openPrivacy()"></input>
				<input class="header link" style="padding-left: 10px; padding-right: 10px;" type="button" value="Report Bug" onclick="openBug()"></input>
				<input class="header link" style="padding-left: 10px; padding-right: 10px;" type="button" value="About Us" onclick="openAbout()"></input>
				<?php
					$query = "SELECT user_id FROM town_" . $townID . ";";
					$ownerID = mysqli_fetch_assoc(mysqli_query($conn, $query))["user_id"];
		
					if($ownerID != $userID)
						echo '<input class="header link" style="padding-left: 10px; padding-right: 10px;" type="button" value="Leave Game" onclick="openLeave()"></input>';
				?>
			</nav>
		</td>
	</table>
</div>

<div id="header-mobile">
	<i id="menu-mobile" class="fas fa-bars" onclick="openMenu();"></i>
	<div id="logo-mobile"><h2><?php echo $town; ?></h2></div>
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
			$query = "SELECT user_id FROM town_" . $townID . ";";
			$ownerID = mysqli_fetch_assoc(mysqli_query($conn, $query))["user_id"];

			if($ownerID != $userID)
				echo '<input class="header link" style="padding: 10px 20px 10px 20px;" type="button" value="Leave Game" onclick="openLeave()"></input>';
		?>
	</nav>
</div>

<div id="town-players">
	<h2 id="town-name" style="margin-bottom: 10px;"><?php echo $town; ?></h2>
	<div id="player-cards">
		<?php
			$query = "SELECT user_id, name, avatar FROM town_" . $townID . ";";
		
			if($result = mysqli_query($conn, $query))
				while($row = mysqli_fetch_assoc($result)) {
					$name = $row["name"];
					if($userID == $row["user_id"])
						$name = $name . " <b>(You)</b>";
					echo '<figure style="margin: 10px; display: inline-block;"><img style="height: 22vh; max-height: 150px;" src="'. $row["avatar"] .'"></img><figcaption style="width: 17vh; white-space: nowrap; overflow: auto;">' . $name . '</figcaption></figure>';
				}
		?>
	</div>

	<p id="share">Your Town ID is <b><?php echo $townID; ?></b>. <span class="tooltip link3" onclick="copyText(townID);">Click here<span id="copy" class="tooltiptext copy">Copy</span></span> to copy.</p>
	<?php
		$query = "SELECT user_id FROM town_" . $townID . ";";
		$ownerID = mysqli_fetch_assoc(mysqli_query($conn, $query))["user_id"];
		
		if($ownerID === $userID)
			echo '<input id="start" class="btn" type="button" value="Start Game"></input>';
	?>
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
		<input id="submit-report" class="btn" type="button" style="margin-top: 10px;" value="Submit Bug Report"></input>
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

<div id="leave-modal" class="modal">
	<table cellpadding="0" cellspacing="0" style="width: 100%;">
		<td class="header2" style="text-align: left;">Warning!</td>
		<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAll()"></i></td>
	</table>
	<table cellpadding="0" cellspacing="0" style="width: 100%;">
		<td><img src="/assets/images/warning.png" style="height: 50px;"></img></td>
		<td><p style="padding: 0; margin: 0;">Are you sure you want to leave this game? You can always join back using the <b>Town ID</b>.</p></td>
	</table>
	<table align="right">
		<td style="text-align: right; padding-right: 2.5px;"><input class="btn3" type="button" value="Cancel" onclick="closeAll()"></td>
		<td style="text-align: right; padding-left: 2.5px;"><input id="leave-game" class="btn" type="button" value="Yes, I'm sure"></td>
	</table>
</div>

<div id="has-started" style="display: none;">
	<?php
		$query = "SELECT has_started FROM town_details WHERE town_id = '$townID';";
		if(mysqli_fetch_assoc(mysqli_query($conn, $query))["has_started"])
			echo '<span>Let the games begin!</span>';
		
		mysqli_close($conn);
	?>
</div>
<script>
	function vhCalc() {
	    let vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
    }
    
    vhCalc();
    
    window.addEventListener('resize', () => {
    	vhCalc();
    });

	function buildTown(response) {
		if(response != "Success!") {
			closeAll();
			setTimeout(function() {
				document.getElementById('error-message').innerHTML = response;
				document.getElementById('error-modal').classList.add("show-modal");
				document.getElementById('modal-background').style.display = "block";
			}, 500);
			
			$('#start').prop('disabled', false);
			$('#start').val('Start Game');
		}
	}
	
	$('#start').on('click', function (event) {
		$('#start').prop('disabled', true);
		$('#start').val('Please wait...');
	
		$.ajax({
			type: 'POST',
			url: 'build-town.php',
			error: function() {
				closeAll();
				setTimeout(function() {
					document.getElementById('error-message').innerHTML = 'Sorry, we are having some trouble communicating with our servers. Please check your internet connection.';
					document.getElementById('error-modal').classList.add("show-modal");
					document.getElementById('modal-background').style.display = "block";
				}, 500);
				
				$('#start').prop('disabled', false);
				$('#start').val('Start Game');
			}
		}).then(response => buildTown(response));
	});

	var lounge = setInterval(function(){
		$("#player-cards").load("/lounge.php #player-cards > *");
		$("#has-started").load("/lounge.php #has-started > *");
		
		var x = document.getElementById('has-started').innerHTML.trim();
		if(x != '') {
			clearInterval(lounge);
			$("body").load("/game.php");
		}
	}, 1000);

	function submitReport(response) {
		if(response === "Success!") {
			document.getElementById('success-bug').style.display = "block";
			document.getElementById('report').value = "";
		}
		else {
			closeAll();
			setTimeout(function() {
				document.getElementById('error-message').innerHTML = response;
				document.getElementById('error-modal').classList.add("show-modal");
				document.getElementById('modal-background').style.display = "block";
			}, 500);
		}

		$('#submit-report').prop('disabled', false);
		$('#submit-report').val('Submit Bug Report');
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
			},
			error: function() {
				closeAll();
				setTimeout(function() {
					document.getElementById('error-message').innerHTML = 'Sorry, we are having some trouble communicating with our servers. Please check your internet connection.';
					document.getElementById('error-modal').classList.add("show-modal");
					document.getElementById('modal-background').style.display = "block";
				}, 500);
				
				$('#submit-report').prop('disabled', false);
				$('#submit-report').val('Submit Bug Report');
			}
		}).then(response => submitReport(response));
	});
	
	function leaveGame(response) {
		if(response === "Success!") {
			window.location.href = window.location.href;
		}
		else {
			closeAll();
			setTimeout(function() {
				document.getElementById('error-message').innerHTML = response;
				document.getElementById('error-modal').classList.add("show-modal");
				document.getElementById('modal-background').style.display = "block";
			}, 500);
			
			$('#leave-game').prop('disabled', false);
			$('#leave-game').val("Yes, I'm sure");
		}
	}

	$('#leave-game').on('click', function () {
		$('#leave-game').prop('disabled', true);
		$('#leave-game').val('Please wait...');
	
		$.ajax({
			type: 'POST',
			url: 'leave-game.php',
			data: {
				check: 'true'
			},
			error: function() {
				closeAll();
				setTimeout(function() {
					document.getElementById('error-message').innerHTML = 'Sorry, we are having some trouble communicating with our servers. Please check your internet connection.';
					document.getElementById('error-modal').classList.add("show-modal");
					document.getElementById('modal-background').style.display = "block";
				}, 500);
				
				$('#leave-game').prop('disabled', false);
				$('#leave-game').val("Yes, I'm sure");
			}
		}).then(response => leaveGame(response));
	});
</script>

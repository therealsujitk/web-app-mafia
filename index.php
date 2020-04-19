<!DOCTYPE HTML PUBLIC>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="./assets/main.css">
		<link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
		<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
		<meta content="utf-8" http-equiv="encoding">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
		<script src="http://code.jquery.com/jquery-3.5.0.js"></script>
		<script src="./assets/main.js"></script>
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
		<div align="center">
			<table id="user-details">
				<tr>
					<td>
						<img id="avatar" src="./assets/avatar_01.png">
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
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closePrivacy()"></i></td>
			</table>
			<p>We don't store shit.</p>
		</div>
		
		<div id="bug-modal" class="modal">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Report Bug</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeBug()"></i></td>
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
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAbout()"></i></td>
			</table>
			<p>Made with love by a team of talented people from <b>BinaryStack</b>.</p>
			<p>Built By: <b><a class="link2" href="https://instagram.com/abishek_devendran/">@AbishekDevendran</a></b> & <b><a class="link2" href="https://instagram.com/therealsujitk">@therealsujitk</a></b>.</p>
		</div>
		
		<div id="create-modal" class="modal">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Create a Town</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeCreate()"></i></td>
			</table>
			<form action="initialize-town.php" method="POST" style="margin: 0;">
				<table cellpadding="0" cellspacing="0" style="width: 100%;">
					<tr>
						<td style="padding-right: 1px;"><span>Town's name:</span></td>
						<td style="padding-left: 1px;"><input id="town" class="text-box" type="text" autocomplete="off" spellcheck="false" placeholder="(optional)" name="town"></input></td>
					</tr>
					<tr>
						<td style="padding-right: 1px;"><span>Mob's name:</span></td>
						<td style="padding-left: 1px;"><input id="mob" class="text-box" type="text" autocomplete="off" spellcheck="false" placeholder="(optional)" name="mob"></input></td>
					</tr>
				</table>
				<button class="btn" type="submit" style="margin: 10px;">Create Town</button></a>
			</form>
		</div>
		
		<div id="join-modal" class="modal">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Join a Town</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeJoin()"></i></td>
			</table>
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td style="padding-right: 1px;"><span>Town ID:</span></td>
				<td style="padding-left: 1px;"><input id="id" class="text-box" type="text" autocomplete="off" spellcheck="false"></input></td>
			</table>
			<a href=""><input class="btn" type="button" value="Join Town"  style="margin: 10px;"></input></a>
		</div>
	</body>
	
	<script>
		$(function () {
			$('#bug-report').on('submit', function (event) {
				// using this page stop being refreshing 
				event.preventDefault();

				$.ajax({
					type: 'POST',
					url: 'report-bug.php',
					data: $('#bug-report').serialize(),
					success: function () {
						document.getElementById('success-bug').style.display = "block";
					},
					error: function () {
						document.getElementById('error-bug').style.display = "block";
					}
				});
			});
		});
		
		function getCookie(cname) {
			// Split cookie string and get all individual name=value pairs in an array
			var cookieArr = document.cookie.split(";");

			// Loop through the array elements
			for(var i = 0; i < cookieArr.length; i++) {
				var cookiePair = cookieArr[i].split("=");

				/* Removing whitespace at the beginning of the cookie name
				and compare it with the given string */
				if(cname == cookiePair[0].trim()) {
					// Decode the cookie value and return
					return decodeURIComponent(cookiePair[1]);
				}
			}

			// Return null if not found
			return null;
		}
		
		if(getCookie('name') != null)
			document.getElementById('name').value = getCookie('name');
		
		if(getCookie('avatar') != null)
			document.getElementById('avatar').src = getCookie('avatar');
	</script>	
</html>


<!DOCTYPE HTML PUBLIC>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="./assets/main.css">
		<link href="https://fonts.googleapis.com/css2?family=Raleway&display=swap" rel="stylesheet">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js"></script>
		<script src="./assets/main.js"></script>
		<title>Mafia</title>
	</head>
	<body>
		<div id="header">
			<table cellpadding="0" cellspacing="0" style="width: 100%; padding-left: 1%; padding-right: 1%;">
				<td><h1 style="color: white; margin: 0;">Mafia</h1></td>
				<td style="text-align: right;">
					<nav>
						<input class="header link" type="button" value="Privacy Policy" onclick="openPrivacy()"></input>
						<input class="header link" type="button" value="Report Bug" onclick="openBug()"></input>
						<input class="header link" type="button" value="About Us" onclick="openAbout()"></input>
					</nav>
				</td>
			</table>
		</div>
		<div align="center">
			<table id="user-details">
				<td>
					<div>
						<img id="avatar" src="./assets/avatar_01.png">
						<table cellpadding="0" cellspacing="0" width="100%">
							<td style = "text-align: right; padding-right: 15px;"><i class="btn2 fas fa-caret-left fa-3x" onclick="prev()"></i></td>
							<td style = "text-align: left; padding-left: 15px;"><i class="btn2 fas fa-caret-right fa-3x" onclick="next()"></i></input></td>
						</table>
					</div>
				</td>
				<td>
					<div>
						<table cellpadding="0" cellspacing="0" style="margin-top: -70px;" width="100%">
							<td style="padding-right: 1px;"><span>Name:</span></td>
							<td style="padding-left: 1px;"><input id="name" type="text" autocomplete="off" spellcheck="false"></input></td>
						</table>
						<span id="name-error" style="padding-left: 10px; color: #c80000; display: none;">Error! Please enter a valid name.</span>
						<table cellpadding="0" cellspacing="0">
							<td><input class="btn" type="button" value="Create a Town" onclick="openCreate()"></input></td>
							<td><input class="btn" type="button" value="Join a Town" onclick="openJoin()"></input></td>
						</table>
					</div>
				</td>
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
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td><textArea id="name" placeholder="Write a bug report..."></textArea></td>
			</table>
			<a href=""><input class="btn" type="button" value="Submit Bug Report"  style="margin: 10px;"></input></a>
		</div>
		
		<div id="about-modal" class="modal" style="text-align: left;">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">About Us</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeAbout()"></i></td>
			</table>
			<p>Made with love by a team of talented people from <b>BinaryStack</b>.</p>
			<p>Built By: @AbishekDevendran & @therealsujitk</p>
		</div>
		
		<div id="create-modal" class="modal">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Create a Town</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeCreate()"></i></td>
			</table>
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<tr>
					<td style="padding-right: 1px;"><span>Town's name:</span></td>
					<td style="padding-left: 1px;"><input id="name" type="text" autocomplete="off" spellcheck="false" placeholder="(optional)"></input></td>
				</tr>
				<tr>
					<td style="padding-right: 1px;"><span>Mob's name:</span></td>
					<td style="padding-left: 1px;"><input id="name" type="text" autocomplete="off" spellcheck="false" placeholder="(optional)"></input></td>
				</tr>
			</table>
			<a href=""><input class="btn" type="button" value="Create Town"  style="margin: 10px;"></input></a>
		</div>
		
		<div id="join-modal" class="modal">
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td class="header2" style="text-align: left;">Join a Town</td>
				<td style="text-align: right;"><i class="header link fas fa-times" onclick="closeJoin()"></i></td>
			</table>
			<table cellpadding="0" cellspacing="0" style="width: 100%;">
				<td style="padding-right: 1px;"><span>Town ID:</span></td>
				<td style="padding-left: 1px;"><input id="name" type="text" autocomplete="off" spellcheck="false"></input></td>
			</table>
			<a href=""><input class="btn" type="button" value="Join Town"  style="margin: 10px;"></input></a>
		</div>
	</body>
</html>


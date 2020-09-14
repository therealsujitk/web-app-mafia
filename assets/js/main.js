const wamp = new thruway.Client('ws://localhost:3000', 'mafia');

var i = 0;
var newsInterval;

//All Pages
function vhCalc() {
	let vh = window.innerHeight * 0.01;
    document.documentElement.style.setProperty('--vh', `${vh}px`);
}

vhCalc();
window.addEventListener('resize', () => {
	vhCalc();
});

function openAbout() {
	let x = document.getElementById('about-modal');
	let y = document.getElementById('modal-background');
	
	x.classList.add("show-modal");
	y.style.display = "block";
}

function openBug() {
	let x = document.getElementById('bug-modal');
	let y = document.getElementById('modal-background');
	
	x.classList.add("show-modal");
	y.style.display = "block";

	setTimeout(function() {
		document.getElementById('report').focus();
	}, 500);
}

function openPrivacy() {
	let x = document.getElementById('privacy-modal');
	let y = document.getElementById('modal-background');
	
	x.classList.add("show-modal");
	y.style.display = "block";
}

function openMenu() {
	let x = document.getElementById('nav-mobile');
	let y = document.getElementById('menu-background');
	
	x.classList.add("show-menu");
	y.style.display = "block";
}

function closeMenu() {
	let x = document.getElementById('nav-mobile');
	let y = document.getElementById('menu-background');
	
	x.classList.add("hide-menu");
	setTimeout(function() {
		x.classList.remove("show-menu");
		x.classList.remove("hide-menu");
		y.style.display = "none";
	}, 500);
}

function closeAll() {
	let a = document.getElementById('modal-background');
	
	if(document.getElementById('privacy-modal')) {
		let x = document.getElementById('privacy-modal')
		x.classList.add("hide-modal");
		setTimeout(function() {
			x.classList.remove("show-modal");
			x.classList.remove("hide-modal");
		}, 500);
	}
	if(document.getElementById('bug-modal')) {
		let x = document.getElementById('bug-modal')
		x.classList.add("hide-modal");
		setTimeout(function() {
			x.classList.remove("show-modal");
			x.classList.remove("hide-modal");
		}, 500);
		document.getElementById('success-bug').style.display = "none";
	}
	if(document.getElementById('about-modal')) {
		let x = document.getElementById('about-modal')
		x.classList.add("hide-modal");
		setTimeout(function() {
			x.classList.remove("show-modal");
			x.classList.remove("hide-modal");
		}, 500);
	}
	if(document.getElementById('create-modal')) {
		let x = document.getElementById('create-modal')
		x.classList.add("hide-modal");
		setTimeout(function() {
			x.classList.remove("show-modal");
			x.classList.remove("hide-modal");
		}, 500);
	}
	if(document.getElementById('join-modal')) {
		let x = document.getElementById('join-modal')
		x.classList.add("hide-modal");
		setTimeout(function() {
			x.classList.remove("show-modal");
			x.classList.remove("hide-modal");
		}, 500);
	}
	if(document.getElementById('error-modal')) {
		let x = document.getElementById('error-modal')
		x.classList.add("hide-modal");
		setTimeout(function() {
			x.classList.remove("show-modal");
			x.classList.remove("hide-modal");
		}, 500);
	}
	if(document.getElementById('role-modal')) {
		let x = document.getElementById('role-modal')
		x.classList.add("hide-modal");
		setTimeout(function() {
			x.classList.remove("show-modal");
			x.classList.remove("hide-modal");
		}, 500);
	}
	
	if(document.getElementById('vote-modal')) {
		let x = document.getElementById('vote-modal')
		x.classList.add("hide-modal");
		setTimeout(function() {
			x.classList.remove("show-modal");
			x.classList.remove("hide-modal");
		}, 500);
	}
	
	if(document.getElementById('leave-modal')) {
		let x = document.getElementById('leave-modal')
		x.classList.add("hide-modal");
		setTimeout(function() {
			x.classList.remove("show-modal");
			x.classList.remove("hide-modal");
		}, 500);
	}
	
	setTimeout(function() {
		a.style.display = "none";
	}, 500);
}

function reportBugResponse(response) {
	if(response === "success") {
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

	var submit = document.getElementById('submit-report');
	submit.disabled = false;
	submit.value = "Submit Bug Report";
}

function reportBug() {
	let report = document.getElementById('report').value;
	if(report.trim() == "") {
		closeAll();
		document.getElementById('report').value = "";
		return;
	}
	var submit = document.getElementById('submit-report');
	submit.disabled = true;
	submit.value = "Please wait...";

	$.ajax({
		type: 'POST',
		url: '/resources/report-bug.php',
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
			
			submit.disabled = false;
			submit.value = "Submit Bug Report";
		}
	}).then(response => reportBugResponse(response));
}

//index.php
function setIndex() {	
	if(getCookie('name') != null) {
		document.getElementById('name').value = getCookie('name');
		document.getElementById('name-mobile').value = getCookie('name');
	}
	
	var avatarID = Math.round(Math.random() * 25) + 1;
	if(getCookie('avatar') != null) {
		document.getElementById('avatar').src = getCookie('avatar');
	}
	else if(avatarID < 10) {
		document.getElementById('avatar').src = '/assets/avatars/avatar_0' + avatarID + '.png';
	}
	else {
		document.getElementById('avatar').src = '/assets/avatars/avatar_' + avatarID + '.png';
	}

	setTimeout(function() {
		document.getElementById('name').focus();
		if(window.innerWidth < 600)
			document.getElementById('name-mobile').focus();
	}, 500);

	document.getElementById('name').addEventListener("keyup", function(e) {
		if(e.key === "Enter")
			joinTown();
	});

	document.getElementById('name-mobile').addEventListener("keyup", function(e) {
		if(e.key === "Enter")
			joinTown();
	});
	
	document.getElementById('town').addEventListener("keyup", function(e) {
		if(e.key === "Enter")
			document.getElementById('mob').focus();
	});

	document.getElementById('mob').addEventListener("keyup", function(e) {
		if(e.key === "Enter")
			createTown();
	});

	document.getElementById('town-id').addEventListener("keyup", function(e) {
		if(e.key === "Enter")
			joinTown();
	});
}

function setCookie(i) {
	let d = new Date();
	d.setTime(d.getTime() + (30*24*60*60*1000));
	let expires = d.toUTCString();
	
	if(i)
		var name = document.getElementById('name').value;
	else
		var name = document.getElementById('name-mobile').value;
	let avatar = document.getElementById('avatar').src;
	
	document.cookie = "name=" + name + ";expires=" + expires + ";path=/;SameSite=Lax";
	document.cookie = "avatar=" + avatar + ";expires=" + expires + ";path=/;SameSite=Lax";
}

function openCreate(i) {
	document.getElementById('name-error').style.display = "none";
	document.getElementById('name-error-mobile').style.display = "none";
	
	if(i)
		var name = document.getElementById('name').value;
	else
		var name = document.getElementById('name-mobile').value;
		
	if(name.trim() === '') {
		document.getElementById('name-error').style.display = "block";
		document.getElementById('name-error-mobile').style.display = "block";
		return;
	}

	let x = document.getElementById('create-modal');
	let y = document.getElementById('modal-background');
	
	x.classList.add("show-modal");
	y.style.display = "block";

	setTimeout(function() {
		document.getElementById('town').focus();
	}, 500);

	setCookie(i);
}

function openJoin(i) {
	document.getElementById('name-error').style.display = "none";
	
	if(i)
		var name = document.getElementById('name').value;
	else
		var name = document.getElementById('name-mobile').value;
	
	if(name.trim() === '') {
		document.getElementById('name-error').style.display = "block";
		return;
	}

	let x = document.getElementById('join-modal');
	let y = document.getElementById('modal-background');
	
	x.classList.add("show-modal");
	y.style.display = "block";

	setTimeout(function() {
		document.getElementById('town-id').focus();
	}, 500);	
	
	setCookie(i);
}

function next() {
	let x = document.getElementById('avatar');
	let index = x.src.slice(-6, -4);

	if(index == 26)
		index = 1;
	else
		++index;
	
	if(index >= 10)
		x.src = './assets/avatars/avatar_' + index + '.png';
	else
		x.src = './assets/avatars/avatar_0' + index + '.png';
}

function prev() {
	let x = document.getElementById('avatar');
	let index = x.src.slice(-6, -4);

	if(index == 1)
		index = 26;
	else
		--index;
	
	if(index >= 10)
		x.src = './assets/avatars/avatar_' + index + '.png';
	else
		x.src = './assets/avatars/avatar_0' + index + '.png';
}

function createTownResponse(response) {
	if(response.slice(0, 7) === "success") {
		$("body").load("lobby.php", function(response, status) {
			if(status !=  "success") {
				document.getElementById('splash-background').classList.add('hide-splash');
				document.getElementById('splash').classList.add('hide-splash');
				closeAll();
				setTimeout(function() {
					document.getElementById('splash-background').classList.remove('show-splash');
					document.getElementById('splash-background').classList.remove('hide-splash');
					document.getElementById('splash').classList.remove('show-splash');
					document.getElementById('splash').classList.remove('hide-splash');
					document.getElementById('error-message').innerHTML = 'Sorry, we are having some trouble communicating with our servers. Please try refreshing this page.';
					document.getElementById('error-modal').classList.add("show-modal");
					document.getElementById('modal-background').style.display = "block";
				}, 500);
			}
		});
	}
	else {
		closeAll();
		setTimeout(function() {
			document.getElementById('error-message').innerHTML = response;
			document.getElementById('error-modal').classList.add("show-modal");
			document.getElementById('modal-background').style.display = "block";
		}, 500);
		
		var create = document.getElementById('create');
		create.disabled = false;
		create.value = "Create Town";
	}
}

function createTown() {
	var create = document.getElementById('create');
	create.disabled = true;
	create.value = "Please wait...";

	let town = document.getElementById('town').value;
	let mob = document.getElementById('mob').value;
	if(window.innerWidth > 600)
		var name = document.getElementById('name').value;
	else
		var name = document.getElementById('name-mobile').value;
	let avatar = document.getElementById('avatar').src;

	$.ajax({
		type: 'POST',
		url: '/resources/initialize-town.php',
		data: {
			town: town,
			mob: mob,
			name: name,
			avatar: avatar
		},
		error: function() {
			closeAll();
			setTimeout(function() {
				document.getElementById('error-message').innerHTML = 'Sorry, we are having some trouble communicating with our servers. Please check your internet connection.';
				document.getElementById('error-modal').classList.add("show-modal");
				document.getElementById('modal-background').style.display = "block";
			}, 500);
			
			create.disabled = false;
			create.value = "Create Town";
		}
	}).then(response => createTownResponse(response));
}

function joinTownResponse(response, townID, joinVal) {
	if(response.slice(0, 7) === "success") {
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
	else {
		closeAll();
		setTimeout(function() {
			document.getElementById('error-message').innerHTML = response;
			document.getElementById('error-modal').classList.add("show-modal");
			document.getElementById('modal-background').style.display = "block";
		}, 500);

		if(document.getElementById('join-town')) {
			var joinButton = document.getElementById('join-town');
			joinButton.disabled = false;
			joinButton.value = joinVal;
	
			var joinButtonM = document.getElementById('join-town-mobile');
			joinButtonM.disabled = false;
			joinButtonM.value = joinVal;
		}

		var join = document.getElementById('join');
		join.disabled = false;
		join.value = "Join Town";
	}
}

function joinTown(i=1) {
	var join = document.getElementById('join');
	join.disabled = true;
	join.value = "Please wait...";

	var joinVal = "";
	if(document.getElementById('join-town')) {
		var joinButton = document.getElementById('join-town');
		joinButton.disabled = true;
		joinVal = joinButton.value;
		joinButton.value = "Please wait...";

		var joinButtonM = document.getElementById('join-town-mobile');
		joinButtonM.disabled = true;
		joinButtonM.value = "Please wait...";
	}

	let townID = document.getElementById('town-id').value;
	if(window.innerWidth > 600)
		var name = document.getElementById('name').value;
	else
		var name = document.getElementById('name-mobile').value;
	let avatar = document.getElementById('avatar').src;

	setCookie(i);

	$.ajax({
		type: 'POST',
		url: '/resources/join-town.php',
		data: {
			townID: townID,
			name: name,
			avatar: avatar
		},
		error: function() {
			closeAll();
			setTimeout(function() {
				document.getElementById('error-message').innerHTML = 'Sorry, we are having some trouble communicating with our servers. Please check your internet connection.';
				document.getElementById('error-modal').classList.add("show-modal");
				document.getElementById('modal-background').style.display = "block";
			}, 500);
			
			join.disabled = false;
			join.value = "Join Town";

			if(document.getElementById('join-town')) {
				joinButton.disabled = false;
				joinButton.value = joinVal;
		
				joinButtonM.disabled = false;
				joinButtonM.value = joinVal;
			}
		}
	}).then(response => joinTownResponse(response, townID, joinVal));
}

function getCookie(cname) {
	var cookieArr = document.cookie.split(";");
	for(var i = 0; i < cookieArr.length; i++) {
		var cookiePair = cookieArr[i].split("=");
		if(cname == cookiePair[0].trim()) {
			return decodeURIComponent(cookiePair[1]);
		}
	}

	return null;
}

//lobby.php
function copyText(value) {
	let tempInput = document.createElement("input");
	tempInput.value = 'http://' + window.location.hostname + '/?i=' + value;
	document.body.appendChild(tempInput);
	tempInput.select();
	document.execCommand("copy");
	document.body.removeChild(tempInput);
	
	let x = document.getElementById('copy');
	x.innerHTML = 'Copied!';
	x.classList.add('copied');
	x.classList.remove('copy');
	
	setTimeout(function () {
		x.innerHTML = 'Copy';
		x.classList.add('copy');
		x.classList.remove('copied');
	}, 3000);
}

function openLeave() {
	let x = document.getElementById('leave-modal');
	let y = document.getElementById('modal-background');
	
	x.classList.add("show-modal");
	y.style.display = "block";
}

function buildTownResponse(response) {
	if(response != "success") {
		conn.send('%' + townID);
		document.getElementById('splash-background').classList.add('hide-splash');
		document.getElementById('splash').classList.add('hide-splash');
		closeAll();
		setTimeout(function() {
			document.getElementById('splash-background').classList.remove('show-splash');
			document.getElementById('splash-background').classList.remove('hide-splash');
			document.getElementById('splash').classList.remove('show-splash');
			document.getElementById('splash').classList.remove('hide-splash');
			document.getElementById('error-message').innerHTML = response;
			document.getElementById('error-modal').classList.add("show-modal");
			document.getElementById('modal-background').style.display = "block";
		}, 500);
		
		var start = document.getElementById('start');
		start.disabled = false;
		start.value = "Start Game";
	}
	else {
		conn.send('$' + townID);
		$("body").load("game.php", function(response, status) {
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
}

function buildTown() {
	var start = document.getElementById('start');
	start.disabled = true;
	start.value = "Please wait...";

	conn.send('!' + townID);
	document.getElementById('splash-background').classList.add('show-splash');
	document.getElementById('splash').classList.add('show-splash');

	$.ajax({
		type: 'POST',
		url: '/resources/build-town.php',
		error: function() {
			conn.send('%' + townID);
			document.getElementById('splash-background').classList.add('hide-splash');
			document.getElementById('splash').classList.add('hide-splash');
			closeAll();
			setTimeout(function() {
				document.getElementById('splash-background').classList.remove('show-splash');
				document.getElementById('splash-background').classList.remove('hide-splash');
				document.getElementById('splash').classList.remove('show-splash');
				document.getElementById('splash').classList.remove('hide-splash');
				document.getElementById('error-message').innerHTML = 'Sorry, we are having some trouble communicating with our servers. Please check your internet connection.';
				document.getElementById('error-modal').classList.add("show-modal");
				document.getElementById('modal-background').style.display = "block";
			}, 500);

			start.disabled = false;
			start.value = "Start Game";
		}
	}).then(response => buildTownResponse(response));
}

function leaveGameResponse(response) {
	if(response.slice(0, 7) === "success") {
		window.location.href = window.location.href;
	}
	else {
		closeAll();
		setTimeout(function() {
			document.getElementById('error-message').innerHTML = response;
			document.getElementById('error-modal').classList.add("show-modal");
			document.getElementById('modal-background').style.display = "block";
		}, 500);
		
		var leave = document.getElementById('leave-game');
		leave.disabled = false;
		leave.value = "Yes, I'm sure";
	}
}

function leaveGame() {
	var leave = document.getElementById('leave-game');
	leave.disabled = true;
	leave.value = "Please wait...";

	$.ajax({
		type: 'POST',
		url: '/resources/leave-town.php',
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
			
			leave.disabled = false;
			leave.value = "Yes, I'm sure";
		}
	}).then(response => leaveGameResponse(response));
}

//game.php
function clientUpdate() {
	//$("#game-display").animate({ scrollTop: $('#game-display').prop("scrollHeight")}, 500);
	$("#game-display").scrollTop($("#game-display").prop("scrollHeight"));

	var results = document.getElementById('results').innerHTML;
	results = results.slice(3, -4).trim();

	if(results != '') {
		closeAll();
		document.getElementById('modal-background2').style.display = "block";
		document.getElementById('win-modal').classList.add("show-modal");
	}
	else {
		if(document.getElementById('alert')) {
			let alert = document.getElementById('alert').innerHTML;
			let notification = document.getElementById('notification');
			notification.innerHTML = alert;
			notification.classList.remove("hide-alert");
			notification.classList.add("show-alert");
		}
		else {
			let notification = document.getElementById('notification');
			if(notification.innerHTML.trim() != '')
				notification.classList.add("hide-alert");
			notification.classList.remove("show-alert");
		}
	}

	printNews();
}

function printNews() {
	if(i) {
		clearInterval(newsInterval);
		i = 0;
	}

	let bold = false;
	var newsBar = document.getElementById('news');
	newsBar.innerHTML = "";
	var news = document.getElementById('news-update').innerHTML.trim();
	newsInterval = setInterval(function() {
		if(i < news.length) {
			if(news[i] === '*') {
				++i;
				if(!bold)
					bold = true;
				else
					bold = false;
			}
			
			if(bold)
				newsBar.innerHTML += news[i].bold();
			else
				newsBar.innerHTML += news[i];
			++i;

			gameSpaceCalc();
		}
		else {
			clearInterval(newsInterval);
			i = 0;
		}
	}, 50);
}

function gameSpaceCalc() {
	let totalHeight = window.innerHeight;
	let headerHeight = document.getElementById('header').offsetHeight;
	let gameIndexHeight = document.getElementById('game-index').offsetHeight;
	let mobileGameNavHeight = document.getElementById('mobile-game-nav-content').offsetHeight;
	let newsBarHeight = document.getElementById('news-bar').offsetHeight;
	let gameFooterHeight = document.getElementById('game-footer').offsetHeight;
	
	if(window.innerWidth < 600) {
		var gameSpaceHeight = totalHeight - mobileGameNavHeight - newsBarHeight - gameFooterHeight - (window.innerWidth/728)*90 - 90 - 20;
			document.documentElement.style.setProperty('--gs', `${gameSpaceHeight}px`);
	}
	else if(window.inneWidth < 728) {
		if ((window.innerWidth/728)*90 > headerHeight) {
			var gameSpaceHeight = totalHeight - (window.innerWidth/728)*90 - gameIndexHeight - newsBarHeight - gameFooterHeight - 100;
			document.documentElement.style.setProperty('--gs', `${gameSpaceHeight}px`);
		}
		else {
			var gameSpaceHeight = totalHeight - headerHeight - gameIndexHeight - newsBarHeight - gameFooterHeight - 100;
			document.documentElement.style.setProperty('--gs', `${gameSpaceHeight}px`);
		}
	}
	else {
		if (90 > headerHeight) {
			var gameSpaceHeight = totalHeight - 90 - gameIndexHeight - newsBarHeight - gameFooterHeight - 150;
			document.documentElement.style.setProperty('--gs', `${gameSpaceHeight}px`);
		}
		else {
			var gameSpaceHeight = totalHeight - headerHeight - gameIndexHeight - newsBarHeight - gameFooterHeight - 150;
			document.documentElement.style.setProperty('--gs', `${gameSpaceHeight}px`);
		}
	}
	
	let gameSpaceHeightR = newsBarHeight + gameFooterHeight + gameSpaceHeight - 30;
	document.documentElement.style.setProperty('--gsr', `${gameSpaceHeightR}px`);
}

function openRole() {
	let x = document.getElementById('role-modal');
	let y = document.getElementById('modal-background');
	
	x.classList.add("show-modal");
	y.style.display = "block";
}

function openVote() {
	let x = document.getElementById('vote-modal');
	let y = document.getElementById('modal-background');
	
	x.classList.add("show-modal");
	y.style.display = "block";
}

function loadL() {
	let x = document.getElementById('button-l');
	let y = document.getElementById('button-r');
	
	x.style.color = '#c80000';
	y.style.color = '#936c6c';
	
	let a = document.getElementById('game-content-l');
	let b = document.getElementById('game-content-r');
	
	a.style.display = 'contents';
	b.style.display = 'none';
}

function loadR() {
	let x = document.getElementById('button-l');
	let y = document.getElementById('button-r');
	
	x.style.color = '#936c6c';
	y.style.color = '#c80000';
	
	let a = document.getElementById('game-content-l');
	let b = document.getElementById('game-content-r');
	
	a.style.display = 'none';
	b.style.display = 'contents';
}

function sendMessageResponse(response, message) {
	if(response === "success") {
		conn.send('^' + townID);

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
	else {
		closeAll();
		setTimeout(function() {
			document.getElementById('error-message').innerHTML = response;
			document.getElementById('error-modal').classList.add("show-modal");
			document.getElementById('modal-background').style.display = "block";
		}, 500);
		
		document.getElementById('chat-box').value = message;
	}
}

function sendMessage() {
	var message = document.getElementById('chat-box').value;
	document.getElementById('chat-box').value = "";

	$.ajax({
		type: 'POST',
		url: '/resources/send-message.php',
		data: {
			message: message
		},
		error: function() {
			closeAll();
			setTimeout(function() {
				document.getElementById('error-message').innerHTML = 'Sorry, we are having some trouble communicating with our servers. Please check your internet connection.';
				document.getElementById('error-modal').classList.add("show-modal");
				document.getElementById('modal-background').style.display = "block";
			}, 500);
			
			document.getElementById('chat-box').value = message;
		}
	}).then(response => sendMessageResponse(response, message));
}

function enterMessage(event) {
	let sendAbility = document.getElementById('send').disabled;
	if(event.keyCode == 13 && !sendAbility) {
		sendMessage();
	}
}

function registerVoteResponse(response) {
	$("#vote-modal").load("game.php #vote-modal > *", function(response, status) {
		if(status !=  "success") {
			closeAll();
			setTimeout(function() {
				document.getElementById('error-message').innerHTML = 'Sorry, we are having some trouble communicating with our servers. Please try refreshing this page.';
				document.getElementById('error-modal').classList.add("show-modal");
				document.getElementById('modal-background').style.display = "block";
			}, 500);
		}
	});

	if(response.slice(0, 7) === "success") {
		let notification = document.getElementById('notification');
		notification.classList.add("hide-alert");
		
		if(response.slice(7) === '1') {
			conn.send('#' + townID);
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
	}
	else {
		closeAll();
		setTimeout(function() {
			document.getElementById('error-message').innerHTML = response;
			document.getElementById('error-modal').classList.add("show-modal");
			document.getElementById('modal-background').style.display = "block";
		}, 500);
		
	}
}

function registerVote(role, vote) {
	if(role != 'sheriff')
		closeAll();
	$.ajax({
		type: 'POST',
		url: '/resources/register-vote.php',
		data: {
			role: role,
			vote: vote
		},
		success: function() {
			document.getElementById('candidates').innerHTML = '<p>Loading...</p>';
		},
		error: function() {
			closeAll();
			setTimeout(function() {
				document.getElementById('error-message').innerHTML = 'Sorry, we are having some trouble communicating with our servers. Please check your internet connection.';
				document.getElementById('error-modal').classList.add("show-modal");
				document.getElementById('modal-background').style.display = "block";
			}, 500);
		}
	}).then(response => registerVoteResponse(response));
}

function restartGameResponse(response) {
	if(response === "success") {
		conn.send('@' + townID);
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
	else {
		closeAll();
		setTimeout(function() {
			document.getElementById('error-message').innerHTML = response;
			document.getElementById('error-modal').classList.add("show-modal");
			document.getElementById('modal-background').style.display = "block";
		}, 500);
		
		var restart = document.getElementById('restart-game');
		restart.disabled = false;
		restart.value = "Back to Lobby";
	}
}

function restartGame() {
	var restart = document.getElementById('restart-game');
	restart.disabled = true;
	restart.value = "Please wait...";

	$.ajax({
		type: 'POST',
		url: '/resources/restart-game.php',
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
			
			restart.disabled = false;
			restart.value = "Back to Lobby";
		}
	}).then(response => restartGameResponse(response));
}

function goHomeResponse(response) {
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
		url: '/resources/leave-game.php',
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
			
			$('#home').prop('disabled', false);
			$('#home').val("Go Home");
		}
	}).then(response => goHomeResponse(response));
}
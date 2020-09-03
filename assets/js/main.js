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
}

function openPrivacy() {
	let x = document.getElementById('privacy-modal');
	let y = document.getElementById('modal-background');
	
	x.classList.add("show-modal");
	y.style.display = "block";
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
	
	setCookie(i);
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

function openLeave() {
	let x = document.getElementById('leave-modal');
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

function next() {
	let x = document.getElementById('avatar');
	let index = x.src.slice(-6, -4);

	if(index == 20)
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
		index = 20;
	else
		--index;
	
	if(index >= 10)
		x.src = './assets/avatars/avatar_' + index + '.png';
	else
		x.src = './assets/avatars/avatar_0' + index + '.png';
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
	
	document.cookie = "name=" + name + ";expires=" + expires + ";path=/";
	document.cookie = "avatar=" + avatar + ";expires=" + expires + ";path=/";
}

function copyText(value) {
	let tempInput = document.createElement("input");
	tempInput.value = value;
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

function displayNews(news, i) {
	let x = document.getElementById('news');
	
	if(i === 0) {
		if(type)
			clearTimeout(type);
		if(typeBold)
			clearTimeout(typeBold);
		x.innerHtml = '';
	}
	
	if (i < news.length) {
		if(news.charAt(i) === '*') {
			++i;
			displayNewsBold(news, i);
		}
		else {
			x.innerHTML += news.charAt(i);
			++i;
			var type = setTimeout(function() {
				displayNews(news, i);
			}, 50);
		}
	}
}

function displayNewsBold(news, i) {
	let x = document.getElementById('news');
	
	if (i < news.length) {
		if(news.charAt(i) === '*') {
			++i;
			displayNews(news, i);
		}
		else {
			x.innerHTML += news.charAt(i).bold();
			i++;
			var typeBold = setTimeout(function() {
				displayNewsBold(news, i);
			}, 50);
		}
	}
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

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

function openCreate() {
	document.getElementById('name-error').style.display = "none";
	if(document.getElementById('name').value.trim() === '') {
		document.getElementById('name-error').style.display = "block";
		return;
	}

	let x = document.getElementById('create-modal');
	let y = document.getElementById('modal-background');
	
	x.classList.add("show-modal");
	y.style.display = "block";
	
	setCookie();
}

function openJoin() {
	document.getElementById('name-error').style.display = "none";
	if(document.getElementById('name').value.trim() === '') {
		document.getElementById('name-error').style.display = "block";
		return;
	}

	let x = document.getElementById('join-modal');
	let y = document.getElementById('modal-background');
	
	x.classList.add("show-modal");
	y.style.display = "block";
	
	setCookie();
}

function openRole() {
	let x = document.getElementById('role-modal');
	let y = document.getElementById('modal-background');
	
	x.classList.add("show-modal");
	y.style.display = "block";
}

function closeAll() {
	let a = document.getElementById('modal-background');
	
	if(document.getElementById('privacy-modal')) {
		let x = document.getElementById('privacy-modal')
		x.classList.remove("show-modal");
	}
	if(document.getElementById('bug-modal')) {
		let x = document.getElementById('bug-modal')
		x.classList.remove("show-modal");
		document.getElementById('success-bug').style.display = "none";
	}
	if(document.getElementById('about-modal')) {
		let x = document.getElementById('about-modal')
		x.classList.remove("show-modal");
	}
	if(document.getElementById('create-modal')) {
		let x = document.getElementById('create-modal')
		x.classList.remove("show-modal");
	}
	if(document.getElementById('join-modal')) {
		let x = document.getElementById('join-modal')
		x.classList.remove("show-modal");
	}
	if(document.getElementById('error-modal')) {
		let x = document.getElementById('error-modal')
		x.classList.remove("show-modal");
	}
	if(document.getElementById('role-modal')) {
		let x = document.getElementById('role-modal')
		x.classList.remove("show-modal");
	}
	
	a.style.display = "none";
}

function next() {
	let x = document.getElementById('avatar');
	let index = x.src.slice(-6, -4);

	if(index == 12)
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
		index = 12;
	else
		--index;
	
	if(index >= 10)
		x.src = './assets/avatars/avatar_' + index + '.png';
	else
		x.src = './assets/avatars/avatar_0' + index + '.png';
}

function setCookie() {
	let d = new Date();
	d.setTime(d.getTime() + (30*24*60*60*1000));
	let expires = d.toUTCString();
	let name = document.getElementById('name').value;
	let avatar = document.getElementById('avatar').src;
	
	document.cookie = "name=" + name + ";expires=" + expires + ";path=/";
	document.cookie = "avatar=" + avatar + ";expires=" + expires + ";path=/";
}

function copyInvite(townID) {
	var link = "https://playmafia.cf/" + townID + "/";
	alert(link);
}

function displayNews(news, i) {
	let x = document.getElementById('news');
	
	if (i < news.length) {
		if(news.charAt(i) === '*') {
			++i;
			displayNewsBold(news, i);
		}
		else {
			x.innerHTML += news.charAt(i);
			++i;
			setTimeout(function() {
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
			setTimeout(function() {
				displayNewsBold(news, i);
			}, 50);
		}
	}
}

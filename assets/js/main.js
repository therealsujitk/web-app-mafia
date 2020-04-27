function openAbout() {
	let x = document.getElementById('about-modal');
	let y = document.getElementById('modal-background');
	
	x.classList.add("show-modal");
	y.style.display = "block";
}

function closeAbout() {
	let x = document.getElementById('about-modal');
	let y = document.getElementById('modal-background');
	
	x.classList.remove("show-modal");
	y.style.display = "none";
}

function openBug() {
	let x = document.getElementById('bug-modal');
	let y = document.getElementById('modal-background');
	
	x.classList.add("show-modal");
	y.style.display = "block";
}

function closeBug() {
	let x = document.getElementById('bug-modal');
	let y = document.getElementById('modal-background');
	
	x.classList.remove("show-modal");
	y.style.display = "none";
	
	document.getElementById('success-bug').style.display = "none";
	document.getElementById('error-bug').style.display = "none";
}

function openPrivacy() {
	let x = document.getElementById('privacy-modal');
	let y = document.getElementById('modal-background');
	
	x.classList.add("show-modal");
	y.style.display = "block";
}

function closePrivacy() {
	let x = document.getElementById('privacy-modal');
	let y = document.getElementById('modal-background');
	
	x.classList.remove("show-modal");
	y.style.display = "none";
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

function closeCreate() {
	let x = document.getElementById('create-modal');
	let y = document.getElementById('modal-background');
	
	x.classList.remove("show-modal");
	y.style.display = "none";
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

function closeJoin() {
	let x = document.getElementById('join-modal');
	let y = document.getElementById('modal-background');
	
	x.classList.remove("show-modal");
	y.style.display = "none";
}

function closeAll() {
	let a = document.getElementById('modal-background');
	let b = document.getElementById('privacy-modal');
	let c = document.getElementById('bug-modal');
	let d = document.getElementById('about-modal');
	let e = document.getElementById('create-modal');
	let f = document.getElementById('join-modal');
	
	a.style.display = "none";
	b.classList.remove("show-modal");
	c.classList.remove("show-modal");
	d.classList.remove("show-modal");
	e.classList.remove("show-modal");
	f.classList.remove("show-modal");
	
	document.getElementById('success-bug').style.display = "none";
	document.getElementById('error-bug').style.display = "none";
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

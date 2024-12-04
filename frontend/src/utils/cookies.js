function setcookie(name, value) {
	if (name) {
		let buildcookie = `${name}=${value};expires=0;path=/;samesite=Strict`;
		document.cookie = buildcookie;
	}
}

function getcookie(name) {
	const cookiename = `${name}=`;
	const cookies = document.cookie.split(';');

	for (let i = 0; i < cookies.length; i++) {
		let cookie = cookies[i].trim();
		if (cookie.indexOf(cookiename) === 0) {
			return cookie.substring(cookiename.length, cookie.length);
		}
	}

	return null;
}

export default {
	setcookie,
	getcookie,
};

import { ref } from 'vue';
import axios from 'axios';

const key_store = 'user' + import.meta.env.VITE_APP_KEY;
const auth_user = ref(localStorage.getItem(key_store));

function set_user(userValue) {
	const str_value = JSON.stringify(userValue);
	localStorage.setItem(key_store, str_value);
	auth_user.value = str_value;
}

function get_user() {
	try {
		return JSON.parse(auth_user.value);
	} catch (e) {
		console.log('Fail parse string to JSON ' + e);
		return null;
	}
}

function clear() {
	localStorage.removeItem(key_store);
}

async function is_authenticated(path) {
	const { data } = await axios.get(import.meta.env.VITE_URL_API + path, {
		headers: {
			Accept: 'application/json',
			Authorization: 'Bearer ' + get_user()?.token
		},
	});

	return data;
}

export default {
	set_user,
	get_user,
	clear,
	is_authenticated,
};

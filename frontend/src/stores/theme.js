import { ref } from 'vue';

const key_store = 'theme' + import.meta.env.VITE_APP_KEY;
const theme = ref(localStorage.getItem(key_store));

function set_theme(themeValue) {
	localStorage.setItem(key_store, themeValue);
	theme.value = themeValue;
}

function get_theme()
{
  return theme.value
}

function apply_theme() {
	const screen = document.getElementById('screen');
	if (screen) {
		screen.classList.remove('light');
		screen.classList.remove('dark');
		screen.classList.add(theme.value ?? 'dark');
	}
}

function clear() {
	localStorage.removeItem(key_store);
}

export default {
	theme: theme.value,
	set_theme,
	get_theme,
	apply_theme,
	clear,
};

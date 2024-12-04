import '@/assets/css/main.css';

import { createApp } from 'vue';
import { createPinia } from 'pinia';

import App from './App.vue';
import router from './router';

const app = createApp(App);

app.use(createPinia());
app.use(router);

app.provide('sysapp', {
	name: import.meta.env.VITE_APP_NAME ?? 'To Do Now',
	desc: import.meta.env.VITE_APP_DESC ?? 'Um simples app de lista de tarefas',
	copy: import.meta.env.VITE_APP_COPY ?? 'OctusPi Development 2024',
	url: import.meta.env.VITE_APP_URL ?? 'localhost',
	version: import.meta.env.VITE_APP_VERSION ?? '1.0.0 alpha',
});

app.mount('#app');

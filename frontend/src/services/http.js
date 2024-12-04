import axsi from './axsi';
import forms from './forms';
import utils from '@/utils/utils';

async function request(opt, emit, customresp = null, callback = null) {
	utils.load();

	await axsi.axiosInstanceAuth
		.request(opt)
		.then(response => {
			if (response) {
				if (customresp && success(response)) {
					customresp(response, emit);
				}
				defresp(response, emit);
				return;
			}

			emit('callAlert', {
				show: true,
				data: { type: 'danger', msg: 'Falha ao receber dados...' },
			});
		})
		.catch(error => {
			console.log(error.message);
			const resperror = callback ?? defresp;
			emit('callAlert', {
				show: true,
				data: error?.response?.data?.notify ?? {
					type: 'danger',
					msg: 'Que feio servidor, não faz assim!',
				},
			});
			resperror(error?.response, emit);
		})
		.finally(() => {
			utils.load(false);
		});
}

function defresp(resp, emit) {
	const data = resp?.data ?? {};

	if (
		resp.status > 208 &&
		(data?.message === 'Unauthenticated.' ||
			data?.message === 'Unauthorized.')
	) {
		window.location = '/';
	}

	//call redirect
	if (data.redirect) {
		window.location = data.redirect;
	}

	//call notifys
	if (data?.notify) {
		emit('callAlert', { show: true, data: data.notify });
	}
}

function post(url, data, emit, resp = null, back = null) {
	const opt = {
		url: url,
		method: 'POST',
		data: forms.builddata(data),
	};

	request(opt, emit, resp, back);
}

function put(url, data, emit, resp = null, back = null) {
	const opt = {
		url: url,
		method: 'PUT',
		data: forms.builddata(data),
		headers: {
			'Content-Type': 'application/json',
		},
	};

	request(opt, emit, resp, back);
}

function get(url, emit, resp = null, back = null) {
	const opt = {
		method: 'GET',
		url: url,
		headers: {
			'Content-Type': 'application/json',
		},
	};

	request(opt, emit, resp, back);
}

function download(url, emit, resp = null, back = null) {
	const opt = {
		method: 'GET',
		url: url,
		responseType: 'blob',
		headers: {
			'Content-Type': 'application/json',
		},
	};

	request(opt, emit, resp, back);
}

function patch(url, data, emit, resp = null, back = null) {
	const opt = {
		url: url,
		method: 'PATCH',
		data: forms.builddata(data),
	};

	request(opt, emit, resp, back);
}

function destroy(url, data, emit, resp = null, back = null) {
	const opt = {
		url: url,
		method: 'POST',
		data: forms.builddata(data),
	};

	request(opt, emit, resp, back);
}

function success(response) {
	return response?.status >= 200 && response?.status < 300;
}

export default {
	request,
	defresp,
	post,
	put,
	patch,
	get,
	download,
	destroy,
	success,
};

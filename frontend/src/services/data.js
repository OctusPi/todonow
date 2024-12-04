import http from './http';
import forms from './forms';
import notifys from '@/utils/notifys';
class Data {
	constructor(page, emit) {
		this.page = page;
		this.emit = emit;
	}

	ui = (mode = null) => {
		switch (mode) {
			case 'register':
				this.page.ui.register = !this.page.ui.register;
				this.page.valids = {};
				this.page.data = {};
				break;
			case 'update':
				this.page.ui.search = false;
				this.page.ui.register = !this.page.ui.register;
				this.page.valids = {};
				break;
			case 'search':
				this.page.ui.search = !this.page.ui.search;
				this.page.ui.register = false;
				this.page.search = {};
				break;
			case 'prepare':
				this.page.ui.prepare = !this.page.ui.prepare;
				this.page.ui.register = false;
				this.page.ui.search = false;
				break;
			default:
				this.page.ui.register = false;
				break;
		}
	};

	save = (over = null, onSave = () => {}, onList = true) => {
		const validation = forms.checkform(this.page.data, {
			fields: this.page.rules,
			valids: this.page.valids,
		});

		if (!validation.isvalid) {
			this.emit('callAlert', notifys.warning(validation.message));
			return;
		}

		let data = { ...this.page.data };

		if (over) data = Object.assign(data, over);

		http.post(`${this.page.url}/save`, data, this.emit, resp => {
			if (onList) {
				this.list();
			}

			if (onSave) {
				onSave(resp);
			}
		});
	};

	update = (id, onUpdate = () => {}) => {
		http.get(`${this.page.url}/details/${id}`, this.emit, response => {
			this.page.data = response.data;
			onUpdate();
			this.ui('update');
		});
	};

	remove = (id, onRemove = null) => {
		this.emit('callRemove', {
			id: id,
			url: this.page.url,
			search: this.page.search,
			onRemove: onRemove,
		});
	};

	fastremove = (id, onRemove = () => {}) => {
		http.destroy(
			`${this.page.url}/fastdestroy`,
			{ id },
			this.emit,
			resp => {
				if (http.success(resp)) {
					this.list();
					onRemove();
				}
			}
		);
	};

	list = () => {
		this.page.search.sent = true;
		http.post(
			`${this.page.url}/list`,
			this.page.search,
			this.emit,
			response => {
				this.page.datalist = response.data ?? [];
				this.ui('list');
			}
		);
	};

	listWithCb = (cb = () => {}) => {
		this.page.search.sent = true;
		http.post(
			`${this.page.url}/list`,
			this.page.search,
			this.emit,
			response => {
				this.page.datalist = response.data ?? [];
				this.ui('list');
				cb();
			}
		);
	};

	selects = (key = null, search = null) => {
		const urlselect =
			key && search
				? `${this.page.url}/selects/${key}/${search}`
				: `${this.page.url}/selects`;
		http.get(urlselect, this.emit, response => {
			this.page.selects = response.data;
		});
	};

	download = (id, name = 'Documento') => {
		http.download(
			`${this.page.url}/download/${id}`,
			this.emit,
			response => {
				if (response.headers['content-type'] !== 'application/pdf') {
					this.emit(
						'callAlert',
						notifys.warning('Arquivo Indisponível')
					);
					return;
				}
				const url = URL.createObjectURL(
					new Blob([response.data], { type: 'application/pdf' })
				);
				const link = document.createElement('a');
				link.href = url;
				link.download = `${name}-${id}.pdf`;
				document.body.appendChild(link);
				link.click();
				window.URL.revokeObjectURL(url);
			}
		);
	};

	listForSearch = (...preseted) => {
		let isFilled = false;
		for (let [key, value] of Object.entries(this.page.search)) {
			if (value && !preseted.includes(key)) {
				this.list();
				isFilled = true;
				break;
			}
		}
		if (!isFilled) {
			this.emit(
				'callAlert',
				notifys.warning('Preencha ao menos um campo para continuar')
			);
		}
	};

	upload = (event, field) => {
		const file = event.target.files[0];
		if (file) {
			const reader = new FileReader();
			reader.readAsDataURL(file);
			reader.onloadend = () => {
				this.page.data[field] = reader.result;
			};
		}
	};
}

export default Data;

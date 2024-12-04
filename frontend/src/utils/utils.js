function load(status = true) {
	const element = document.getElementById('load-wall');
	if (element) {
		status
			? element.classList.remove('d-none')
			: element.classList.add('d-none');
	}
}

function dateNow() {
	return new Date().toLocaleDateString();
}

function randCode(len = 12) {
	const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	let final = '';

	for (let i = 0; i < len; i++) {
		final += chars[Math.floor(Math.random() * chars.length)];
	}

	return final;
}

function getTxt(objs, key_subject, key_search = 'id', key_txt = 'title') {
	if (objs) {
		const f = objs.find(o => o[key_search] == key_subject);
		return f ? f[key_txt] : '*****';
	}

	return '*****';
}

function extractTxt(objs, key) {
	if (objs) {
		const txt = objs.map(o => o[key]);
		return txt.join('; ');
	}

	return '*****';
}

function truncate(str, len = 100) {
	if (str == null) {
		return null;
	}

	if (str.length <= len) {
		return str;
	}

	return str.slice(0, len - 3) + '...';
}

function stripHTML(str) {
	const parser = new DOMParser().parseFromString(str, 'text/html');
	return parser.body.textContent;
}

function dateProtocol(pivot, separator = '', rng = null) {
	if (!pivot) {
		return null;
	}

	const d = new Date();

	const date =
		d.getDay().toString().padStart(2, '0') +
		d.getMonth().toString().padStart(2, '0') +
		d.getFullYear().toString().padStart(4, '0');

	const mili = d.getMilliseconds().toString().padStart(4, '0');

	return (
		String(pivot).padStart(3, '0') +
		separator +
		(rng ?? date) +
		separator +
		mili
	);
}

function reduceArrays(a, b, k = 'id') {
	const map = new Map();

	function reduceFunc(arr) {
		if (Array.isArray(arr)) {
			arr.forEach(it => map.set(it[k], it));
		}
	}

	reduceFunc(a);
	reduceFunc(b);

	return Array.from(map.values());
}

function currencyToFloat(currency) {
	if (!currency) {
		return 0;
	}

	if (typeof currency === 'number') {
		return currency;
	}

	let sanitizedString = currency.replace(/\s|R\$|/g, '');
	sanitizedString = sanitizedString.replaceAll(' ', '');
	sanitizedString = sanitizedString.replaceAll('.', '');
	sanitizedString = sanitizedString.replaceAll(',', '.');
	const floatValue = parseFloat(sanitizedString);
	return isNaN(floatValue) ? 0 : floatValue;
}

function floatToCurrency(value) {
	const pvalue =
		typeof value !== 'number' ? this.currencyToFloat(value) : value;

	return Intl.NumberFormat('pt-BR', {
		style: 'currency',
		currency: 'BRL',
	}).format(pvalue);
}

function deepEqual(obj1, obj2) {
	if (
		typeof obj1 === 'object' &&
		obj1 !== null &&
		typeof obj2 === 'object' &&
		obj2 !== null
	) {
		if (Object.keys(obj1).length !== Object.keys(obj2).length) {
			return false;
		}

		for (let key in obj1) {
			if (
				!Object.prototype.hasOwnProperty.call(obj2, key) ||
				!deepEqual(obj1[key], obj2[key])
			) {
				return false;
			}
		}
		return true;
	} else {
		return obj1 === obj2;
	}
}

export default {
	load,
	dateNow,
	randCode,
	getTxt,
	extractTxt,
	truncate,
	stripHTML,
	dateProtocol,
	reduceArrays,
	currencyToFloat,
	floatToCurrency,
	deepEqual,
};

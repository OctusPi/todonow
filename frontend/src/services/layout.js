import { reactive } from 'vue';
import Data from './data';

function buildPage(preloaded = {}) {
	return reactive({
		url: '',
		ui: { register:false, list: true },
		datalist: [],
		header: [],
		selects: {},
		data: {},
		search: {},
		rules: {},
		valids: {},
		...preloaded,
	});
}

export default {
	new: (emit, preloaded = {}) => {
		const page = buildPage(preloaded);
		const action = new Data(page, emit);

		return [page, action];
	}
};

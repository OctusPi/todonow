import { reactive } from 'vue';
import storeEnterprise from '@/stores/enterprise';
import Data from './data';

function buildPage(preloaded = {}) {
	const enterprise = storeEnterprise.get_enterprise();
	return reactive({
		url: '',
		enterprise: enterprise,
		enterprise_name: enterprise?.name,
		ui: { list: true },
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

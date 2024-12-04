import { ArrowDownTrayIcon, ArrowUpOnSquareStackIcon, DocumentTextIcon, PencilSquareIcon, TrashIcon } from "@heroicons/vue/24/outline";

export default class Actions {
	static Create(icon, title, action, modal = null) {
		return { icon, title, action, modal };
	}
	static Edit(action) {
		return Actions.Create(PencilSquareIcon, 'Editar', action);
	}
	static Delete(action) {
		return Actions.Create(
			TrashIcon,
			'Remover',
			action,
			'#modalDelete'
		);
	}
	static FastDelete(action) {
		return Actions.Create(TrashIcon, 'Remover', action);
	}
	static Dowload(action) {
		return Actions.Create(ArrowDownTrayIcon, 'Download', action);
	}
	static Export(icon, action) {
		return Actions.Create(icon, ArrowUpOnSquareStackIcon, action);
	}
	static ModalDetails(action) {
		return Actions.Create(
			DocumentTextIcon,
			'Detalhar',
			action,
			'#modalDetails'
		);
	}
}

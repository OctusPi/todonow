export default class Mounts {
	static Normal(address) {
		return {
			value: address,
			classes: [],
		};
	}

	static Cast(dataset, datasetKey = 'id', datasetCast = 'title') {
		if (!Array.isArray(dataset)) {
			return Mounts.Normal;
		}

		return address => {
			const value = dataset.find(item => item[datasetKey] == address);
			return {
				value: value ? value[datasetCast] : address,
				classes: [],
			};
		};
  }

  static CastKey(dataset) {
		if (!Array.isArray(dataset)) {
			return Mounts.Normal;
		}

		return address => {
			return {
				value: dataset[address] ?? address,
				classes: [],
			};
		};
	}

	static CastVirt(key, compareFunc) {
		return (address, instance) => {
			return {
				value: compareFunc(address, instance._virtual)[key],
				classes: [],
			};
		};
	}

	static Cls(...cls) {
		return address => ({
			value: address,
			classes: cls,
		});
	}

	static Truncate(num = 100) {
		return address => {
			if (num >= String(address).length) {
				return {
					value: address,
					classes: [],
				};
			}
			return {
				value:
					num < address.length
						? String(address).slice(0, num - 3) + '...'
						: address,
				classes: ['cursor-help', 'has-title'],
			};
		};
  }

  static CompareSize(compareValue) {


    return (address, instance) => {
			return {
				value: address,
				classes: [parseFloat(address) <= parseFloat(instance[compareValue]) ? '!text-red-700' : '!text-green-700'],
			};
		};
	}

	static StripHTML() {
		return address => {
			if (!address) {
				return {
					value: address,
					classes: [],
				};
			}
			const parser = new DOMParser().parseFromString(
				String(address),
				'text/html'
			);
			return {
				value: parser.body.textContent,
				classes: [],
			};
		};
	}

	static Status() {
		return address => {
			let colorClass = 'tls-grey';

			switch (address) {
				case 'Inativo':
				case 'Baixa':
				case 'Deserto':
				case 'Fracassado':
				case 'Aprovisionado':
				case 'Em Construção':
					colorClass = 'tls-yellow';
					break;
				case 'Enviado':
				case 'Enviada':
				case 'Aberto':
				case 'Aberta':
				case 'Ativo':
				case 'Ativa':
				case 'Iniciada':
				case 'Finalizado':
				case 'Aprovado':
				case 'Aprovada':
					colorClass = 'tls-blue';
					break;
				case 'Pendente':
				case 'Média':
				case 'Adiado':
				case 'Revogado':
					colorClass = 'tls-orange';
					break;
				case 'Bloqueado':
				case 'Alta':
				case 'Anulado':
				case 'Cancelado':
					colorClass = 'tls-red';
					break;
				case 'Processado':
				case 'Finalizada':
					colorClass = 'tls-grey';
					break;
				default:
					colorClass = 'tls-grey';
					break;
			}

			return {
				value: address,
				classes: ['badge', 'tls', colorClass],
			};
		};
	}

	static Level() {
		return address => {
			if (address >= 225) {
				return {
					value: address,
					classes: ['badge', 'tls', 'tls-red'],
				};
			}
			if (address >= 100) {
				return {
					value: address,
					classes: ['badge', 'tls', 'tls-yellow'],
				};
			}
			return {
				value: address,
				classes: ['badge', 'tls', 'tls-green'],
			};
		};
	}
}

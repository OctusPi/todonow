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

				case 'Pendente':
					colorClass = 'tls-yellow';
					break;
				case 'Finalizado':
				case 'Finalizada':
				case 'Aprovado':
				case 'Aprovada':
					colorClass = 'tls-blue';
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

}

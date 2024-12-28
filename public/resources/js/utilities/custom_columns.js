
const actionColumn = {
	data: null,
	orderable: false,
	searchable: false,
	render: (data, type, row) => {
		const params = Object.entries(row);
		let dataset = "";
		params.forEach((param) => {
			const key = param[0].split(/(?=[A-Z])/).join("-");
			dataset += ` data-${key.substring(0, 2) === "id" || !/(?=[A-Z])/.test(key.substring(0, 1))
					? key
					: "-" + key
				}="${param[1] ?? ""}"`;
		});
		return `
			<button class="btn btn-outline-success btn-update" ${dataset}>
				<i class="bi bi-pencil-square" ${dataset}></i>
			</button>
			<button class="btn btn-outline-danger btn-remove" ${dataset}>
				<i class="bi bi-trash3" ${dataset}></i>
			</button>
		`;
	}
}

const dateColumn = {
	createdAt: {
		data: 'created_at',
		render: function (data, type, row) {
			if (type === 'display' || type === 'filter') {
				return getDate(data, true)
			}
			return data;
		},
	},
	updatedAt: {
		data: 'updated_at',
		render: function (data, type, row) {
			if (type === 'display' || type === 'filter') {
				return getDate(data, true)
			}
			return data;
		},
	},
	saleAt: {
		data: 'sale_date',
		render: function (data, type, row) {
			if (type === 'display' || type === 'filter') {
				return getDate(data, true)
			}
			return data;
		},
	}
}

const getDate = (date, onlyDate = false) => {
	const now = new Date(date);
	const format = (num) => String(num).padStart(2, '0');
	const [day, month, year] = [format(now.getDate()), format(now.getMonth() + 1), now.getFullYear()];
	if (onlyDate) return `${day}-${month}-${year}`;
	const [hours, minutes, seconds] = [format(now.getHours()), format(now.getMinutes()), format(now.getSeconds())];
	return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
};

export {dateColumn, actionColumn};
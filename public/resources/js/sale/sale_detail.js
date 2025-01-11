import { DatatableService } from "../utilities/datatable_service.js";
import { DateFormatter } from "../utilities/date_formatter.js";
import { FormService } from "../utilities/form_service.js";
import { loader } from "../utilities/loader_service.js";
import { disbaledElement, fetchService, getCurrencyMXN, toast } from "../utilities/utilities.js";
import { columns } from "./columns_detail.js";

export class SaleDetail {
	constructor(finishSaleBtn, buyInitBtn, form) {
		this.path = "/app/sales";
		this.form = form
		this._saleId = null;
		this._saleTotal = null;
		this.finishSaleBtn = finishSaleBtn;
		this.save = this.save.bind(this);
		this.buyInitBtn = buyInitBtn;
		this.show = this.show.bind(this);
		this.delete = this.delete.bind(this);
		this.finishSaleBtn.addEventListener('click', () => this.finishSale({ instance: this, event: finishSaleBtn }));
		this.buyInitBtn.addEventListener('click', () => this.buyInit({ instance: this, event: buyInitBtn }));
		this.form.parentNode.parentNode.hidden = true;
		this.finishSaleBtn.hidden = true;
		this.form.sale_detail_code.addEventListener('input', () => { })
		const codeInput = document.querySelector('#sale_detail_code');
		const select = document.querySelector('#sale_detail_product');
		const quantity = document.querySelector("#sale_detail_quantity");
		const unitPrice = document.querySelector("#sale_detail_unit_price");
		const total = document.querySelector("#sale_detail_total_price");
		codeInput?.addEventListener('input', () => {
			console.log('input');
			const value = codeInput.value;
			if (!select) return;
			const option = select.querySelector(`option[data-code="${value}"]`);
			if (!option) return;
			select.value = option.value;
			unitPrice.value = option.dataset.price;
			total.value = SaleDetail.getTotal(quantity.value, unitPrice.value);
		});
		select?.addEventListener('change', () => {
			const option = select.options[select.options.selectedIndex];
			if (!option) return;
			unitPrice.value = option.dataset.price;
			total.value = SaleDetail.getTotal(quantity.value, unitPrice.value);
		});

		quantity?.addEventListener('input', () => {
			total.value = SaleDetail.getTotal(quantity.value, unitPrice.value);
		})

		this.form.addEventListener('submit', this.save);
	}

	get saleId() {
		return this._saleId;
	}

	set saleId(saleId) {
		this._saleId = saleId;
	}
	
	get saleTotal() {
		return this._saleTotal;
	}

	set saleTotal(saleTotal) {
		this._saleTotal = saleTotal;
	}

	static getTotal(quantity, unitPrice) {
		const total = quantity * unitPrice;
		if (isNaN(total)) return 0;
		return total;
	}
	async buyInit({ instance, event }) {
		event.hidden = true;
		const saleId = await instance.generateSale({ instance });
		instance.saleId = saleId;
		this.datatable = new DatatableService(
			'table#table-detail-sale',
			columns,
			this.path + "/detail/all?sale=" + saleId,
			null,
			this.handleBeforeInitializazingTable,
			this.handleAfterInitializazingTable,
			this.show,
			this.delete
		);
		instance.form.parentNode.parentNode.hidden = false;
		const codeInput = document.querySelector('#sale_detail_code');
		codeInput?.focus();
		this.finishSaleBtn.hidden = false;
		instance.form.sale_detail_total_price.disabled = true;
		const spanTotal = document.querySelector('#total-sale-span');
		spanTotal.textContent = getCurrencyMXN(0);
	}

	async show({ instance, data }) {
		const form = document.querySelector(`form[name=sale_detail]`);
		form?.reset();
		if (typeof data === 'object' && data.hasOwnProperty('id')) {
			form.dataset.id = data.id;
			form.method = 'put';
			FormService.setData(form, data);
		}
	}

	async delete({ instance, id }) {
		await fetchService.delete(`/app/sales/detail/save/${id}`);
		await instance.reload();
		toast.success('Se elimino con éxito el registro de venta.');
		const codeInput = document.querySelector('#sale_detail_code');
		codeInput?.focus();
	}

	async generateSale({ instance }) {
		const url = instance.path + "/generate";
		const body = {
			total_amount: 0.0,
			sale_date: DateFormatter.getFormattedDateISO(),
			payment_method: "Efectivo",
			user: null
		};
		//const data = await fetchService.post(url, body);
		instance.form.sale_detail_sale.value = 1//data.id;
		instance.form.hidden = false;
		return 1//data.id;
	}

	async finishSale({instance, event}) {
		disbaledElement(event);
		loader.show();
		const url = instance.path + "/save/" + instance.saleId;
		const body = {
			total_amount: instance.saleTotal ?? 0.0,
		};
		await fetchService.put(url, body);
		instance.form.disabled = false;
		instance.datatable?.destroy();
		instance.buyInitBtn.hidden = false;
		instance.finishSaleBtn.hidden = true;
		instance.form.parentNode.parentNode.hidden = true;
		const spanTotal = document.querySelector('#total-sale-span');
		spanTotal.textContent = getCurrencyMXN(0);
		loader.hide();
	}

	async save(event) {
		event.preventDefault();
		disbaledElement(event.target);
		this.form.sale_detail_total_price.disabled = false;
		let res = null
		if (this.form.dataset.hasOwnProperty('id') && this.form.dataset.id) {
			const formData = FormService.getData(this.form, true, false);
			res = await fetchService.put(this.path + "/detail/save/" + this.form.dataset.id, formData);
		} else {
			const formData = FormService.getData(this.form);
			res = await fetchService.post(this.path + "/detail/save", formData);
		}
		this.form.sale_detail_total_price.disabled = true;
		toast.success('Se agrego el producto');
		this.form.reset();
		const codeInput = document.querySelector('#sale_detail_code');
		codeInput?.focus();
		const spanTotal = document.querySelector('#total-sale-span');
		spanTotal.textContent = getCurrencyMXN(res.total);
		this.saleTotal = res.total;
		await this.datatable.reload();
	}

	handleBeforeInitializazingTable() {
		loader.show("Cargando los productos de esta venta, espere por favor...");
	}
	handleAfterInitializazingTable() {
		loader.hide();
	}
}
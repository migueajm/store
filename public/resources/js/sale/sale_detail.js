import { DatatableService } from "../utilities/datatable_service.js";
import { DateFormatter } from "../utilities/date_formatter.js";
import { FormService } from "../utilities/form_service.js";
import { loader } from "../utilities/loader_service.js";
import { disbaledElement, fetchService, toast } from "../utilities/utilities.js";
import { columns } from "./columns_detail.js";

export class SaleDetail{
	constructor(finishSaleBtn, buyInitBtn, form){
		this.path = "/app/sales";
		this.form = form
		this.finishSaleBtn = finishSaleBtn;
		this.save = this.save.bind(this);
		this.buyInitBtn = buyInitBtn;
		this.show = this.show.bind(this);
		this.delete = this.delete.bind(this);
		this.finishSaleBtn.addEventListener('click', this.finishSale);
		this.buyInitBtn.addEventListener('click', () => {this.buyInit({instance: this, event: buyInitBtn})});
		this.form.parentNode.parentNode.hidden = true;
		this.finishSaleBtn.hidden = true;
		this.form.sale_detail_code.addEventListener('input', () => {})
		const codeInput = document.querySelector('#sale_detail_code');
		const select = document.querySelector('#sale_detail_product');
		const quantity = document.querySelector("#sale_detail_quantity");
		const unitPrice = document.querySelector("#sale_detail_unit_price");
		const total = document.querySelector("#sale_detail_total_price");
		codeInput?.addEventListener('input', () => {
			console.log('input');
			const value = codeInput.value;
			if(!select) return;
			const option = select.querySelector(`option[data-code="${value}"]`);
			if(!option) return;
			select.value = option.value;
			unitPrice.value = option.dataset.price;
			total.value = SaleDetail.getTotal(quantity.value, unitPrice.value);
		});
		select?.addEventListener('change', () => {
			const option = select.options[select.options.selectedIndex];
			if(!option) return;
			unitPrice.value = option.dataset.price;
			total.value = SaleDetail.getTotal(quantity.value, unitPrice.value);
		});

		quantity?.addEventListener('input', () => {
			total.value = SaleDetail.getTotal(quantity.value, unitPrice.value);
		})

		this.form.addEventListener('submit', this.save);
	}

	static getTotal(quantity, unitPrice){
		const total = quantity * unitPrice;
		if(isNaN(total)) return 0;
		return total;
	}
	async buyInit({instance, event}){
		event.hidden = true;
		const saleId = await instance.generateSale({instance});
		this.datatable = new DatatableService(
			'table#table-detail-sale',
			columns,
			this.path + "/detail/all?sale="+saleId,
			null,
			this.handleBeforeInitializazingTable,
			this.handleAfterInitializazingTable,
			this.buyInit,
			this.show,
			this.delete
		);
		instance.form.parentNode.parentNode.hidden = false;
		const codeInput = document.querySelector('#sale_detail_code');
		codeInput?.focus();
		this.finishSaleBtn.hidden = false;
	}

	async show({instance, data}) {
		const form = document.querySelector(`form[name=sale_detail]`);
		form?.reset();
		if(typeof data === 'object' && data.hasOwnProperty('id')){
			form.dataset.id = data.id;
			form.method = 'put';
			FormService.setData(form, data);
		}
	}

	async delete({instance, id}) {
		await fetchService.delete(`/app/sale/detail/save/${id}`);
		await instance.reload();
		toast.success('Se elimino con éxito el registro de venta.');
	}

	async generateSale({instance}) {
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

	async finishSale() {
		const url = this.path + "/save";
		const body = {
			total_amount: this.form.total_amount,
		};
		const id = await fetchService.put(url, body);
		this.form.sale.value = id;
		this.form.disabled = false;
		this.datatable.clear();
		this.datatable.destroy();
		this.buyInitBtn.hidden = false;
		this.finishSaleBtn.hidden = true;
	}

	async save(event) {
		event.preventDefault();
		disbaledElement(event.target);
		if(this.form.dataset.hasOwnProperty('id') && this.form.dataset.id){
			const formData = FormService.getData(this.form, true, false);
			await fetchService.put(this.path+"/detail/save/"+this.form.dataset.id, formData);
		}else{
			const formData = FormService.getData(this.form);
			await fetchService.post(this.path+"/detail/save", formData);
		}
		toast.success('Se agrego el producto');
		this.form.reset();
		await this.datatable.reload();
	}

	handleBeforeInitializazingTable() {
		loader.show("Cargando los productos de esta venta, espere por favor...");
	}
	handleAfterInitializazingTable() {
		loader.hide();
	}
}
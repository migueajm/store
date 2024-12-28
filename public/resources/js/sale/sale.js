import { DatatableService } from "../utilities/datatable_service.js";
import { FormService } from "../utilities/form_service.js";
import { loader } from "../utilities/loader_service.js";
import { disbaledElement, fetchService, toast } from "../utilities/utilities.js";
import { columns } from "./columns.js";

export class Sale{
	constructor(createBtn, showModalBtn, form){
		this.path = "/app/sales";
		this.datatable = new DatatableService(
			'table#table-sales',
			columns,
			this.path + "/all",
			null,
			this.handleBeforeInitializazingTable,
			this.handleAfterInitializazingTable,
			this.show,
			this.delete
		);
		this.form = form
		this.createBtn = createBtn;
		this.save = this.save.bind(this);
		this.showModalBtn = showModalBtn;
		this.show = this.show.bind(this);
		this.delete = this.delete.bind(this);
		this.createBtn.addEventListener('click', this.save);
		this.showModalBtn.addEventListener('click', this.show);
		const modal = document.getElementById('modal-form-sale');
		this.modal = new bootstrap.Modal(modal);
	}

	async show({instance, data}) {
		const form = document.querySelector(`form[name=sale]`);
		form?.reset();
		form.method = 'post';
		form.removeAttribute('data-id');
		if(typeof data === 'object' && data.hasOwnProperty('id')){
			form.dataset.id = data.id;
			form.method = 'put';
			FormService.setData(form, data);
		}
		const modal = document.getElementById('modal-form-sale');
		bootstrap.Modal.getInstance(modal).show();
	}

	async delete({instance, id}) {
		loader.show('Se esta eliminado el registro de venta, espere por favor');
		await fetchService.delete(`/app/sale/save/${id}`);
		await instance.reload();
		loader.hide();
		toast.success('Se elimino con éxito el registro de venta.');
	}

	async save(event) {
		disbaledElement(event.target);
		this.modal.hide();
		loader.show('Generando un nueva venta, espere por favor...');
		if(this.form.dataset.hasOwnProperty('id') && this.form.dataset.id){
			const formData = FormService.getData(this.form, true, false);
			await fetchService.put(this.path+"/save/"+this.form.dataset.id, formData);
		}else{
			const formData = FormService.getData(this.form);
			await fetchService.post(this.path+"/save", formData);
		}
		await this.datatable.reload();
		loader.hide();
		toast.success('Se genero con éxito el registro de venta.');
	}

	handleBeforeInitializazingTable() {
		loader.show("Cargando los registros de ventas, espere por favor...");
	}
	handleAfterInitializazingTable() {
		loader.hide();
	}
}
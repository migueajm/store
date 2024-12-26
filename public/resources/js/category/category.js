import { DatatableService } from "../utilities/datatable_service.js";
import { FormService } from "../utilities/form_service.js";
import { loader } from "../utilities/loader_service.js";
import { disbaledElement, fetchService, toast } from "../utilities/utilities.js";
import { columns } from "./columns.js";

export class Category{
	constructor(createBtn, showModalBtn, categoryForm){
		this.path = "/admin/category";
		this.datatable = new DatatableService(
			'table#table-category',
			columns,
			this.path + "/all",
			null,
			this.handleBeforeInitializazingTable,
			this.handleAfterInitializazingTable,
			this.show,
			this.delete
		);
		this.form = categoryForm
		this.createBtn = createBtn;
		this.save = this.save.bind(this);
		this.showModalBtn = showModalBtn;
		this.show = this.show.bind(this);
		this.delete = this.delete.bind(this);
		this.createBtn.addEventListener('click', this.save);
		this.showModalBtn.addEventListener('click', this.show);
		const modal = document.getElementById('modal-form-category');
		this.modal = new bootstrap.Modal(modal);
	}

	async show({instance, dataset}) {
		const form = document.querySelector(`form[name=category]`);
		form?.reset();
		form.method = 'post';
		form.removeAttribute('data-id');
		if(typeof dataset === 'object' && dataset.hasOwnProperty('id')){
			form.dataset.id = dataset.id;
			form.method = 'put';
			FormService.setData(form, dataset);
		}
		const modal = document.getElementById('modal-form-category');
		bootstrap.Modal.getInstance(modal).show();
	}

	async delete({instance, id}) {
		loader.show('Se esta eliminado la categoria, espere por favor');
		await fetchService.delete(`/admin/category/save/${id}`);
		await instance.reload();
		loader.hide();
		toast.success('Se elimino con éxito la categoria.');
	}

	async save(event) {
		disbaledElement(event.target);
		this.modal.hide();
		loader.show('Generando la nueva categoria, espere por favor...');
		if(this.form.dataset.hasOwnProperty('id') && this.form.dataset.id){
			const formData = FormService.getData(this.form, true, false);
			await fetchService.put(this.path+"/save/"+this.form.dataset.id, formData);
		}else{
			const formData = FormService.getData(this.form);
			await fetchService.post(this.path+"/save", formData);
		}
		await this.datatable.reload();
		loader.hide();
		toast.success('Se genero con éxito la categoria.');
	}

	handleBeforeInitializazingTable() {
		loader.show("Cargando las categorias, espere por favor...");
	}
	handleAfterInitializazingTable() {
		loader.hide();
	}
}
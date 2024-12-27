import { DatatableService } from "../utilities/datatable_service.js";
import { FormService } from "../utilities/form_service.js";
import { loader } from "../utilities/loader_service.js";
import { disbaledElement, fetchService, toast } from "../utilities/utilities.js";
import { columns } from "./columns.js";

export class User{
	constructor(createBtn, showModalBtn, form){
		this.path = "/admin/user";
		this.datatable = new DatatableService(
			'table#table-users',
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
		const modal = document.getElementById('modal-form-user');
		this.modal = new bootstrap.Modal(modal);
		const button = document.querySelector('#user_password_update');
		button.addEventListener('click', () => {
			button.parentNode.hidden = true;
			this.form.user_password.parentNode.parentNode.hidden = false;
		});
	}

	async show({instance, data}) {
		const form = document.querySelector(`form[name=user]`);
		form?.reset();
		form.method = 'post';
		form.removeAttribute('data-id');
		form.user_password.parentNode.parentNode.hidden = false;
		form.user_username.disabled = false;
		form.user_password_update.parentNode.hidden = true;
		form.user_password_update.disabled = true;
		if(typeof data === 'object' && data.hasOwnProperty('id')){
			form.dataset.id = data.id;
			form.user_password.parentNode.parentNode.hidden = true;
			form.user_username.disabled = true;
			form.user_password_update.parentNode.hidden = false;
			form.user_password_update.disabled = false;
			form.method = 'put';
			FormService.setData(form, data);
		}
		const modal = document.getElementById('modal-form-user');
		bootstrap.Modal.getInstance(modal).show();
	}

	async delete({instance, id}) {
		loader.show('Se esta eliminado el usuario, espere por favor');
		await fetchService.delete(`/admin/user/save/${id}`);
		await instance.reload();
		loader.hide();
		toast.success('Se elimino con éxito el usuario.');
	}

	async save(event) {
		disbaledElement(event.target);
		this.modal.hide();
		this.form.user_username.disabled = false;
		loader.show('Generando el usuario, espere por favor...');
		if(this.form.dataset.hasOwnProperty('id') && this.form.dataset.id){
			const formData = FormService.getData(this.form, true, false);
			await fetchService.put(this.path+"/save/"+this.form.dataset.id, formData);
		}else{
			const formData = FormService.getData(this.form);
			await fetchService.post(this.path+"/save", formData);
		}
		await this.datatable.reload();
		loader.hide();
		toast.success('Se genero con éxito el usuario.');
	}

	handleBeforeInitializazingTable() {
		loader.show("Cargando los usuarios, espere por favor...");
	}
	handleAfterInitializazingTable() {
		loader.hide();
	}
}
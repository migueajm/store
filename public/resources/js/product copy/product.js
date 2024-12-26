import { Datatable } from "../utilities/datatable_service.js";
import { loader } from "../utilities/loader_service.js";
import { disbaledElement, fetchService, toast } from "../utilities/utilities.js";
import { columns } from "./columns.js";

export class Product{
	constructor(productForm, showModalBtn){
		this.path = "/admin/product";
		this.datatable = new Datatable(
			'table#table-products',
			columns,
			this.path + "/all",
			null,
			this.handleBeforeInitializazingTable,
			this.handleAfterInitializazingTable,
		);
		this.productForm = productForm;
		this.create = this.create.bind(this);
		this.showModalBtn = showModalBtn;
		this.show = this.show.bind(this);
		this.productForm.addEventListener('submit', this.create);
		this.showModalBtn.addEventListener('click', this.show);
		const modal = document.getElementById('modal-form-product');
		this.modal = new bootstrap.Modal(modal);
	}

	show(id = null) {
		if(id){

		}
		this.modal.show();
	}

	async create(event) {
		disbaledElement(event.target);
		loader.show('Generando el nuevo producto, espere por favor...');
		const formData = new FormData(this.form);
		await fetchService.post(this.path+"/save", formData);
		await this.datatable.reload();
		toast.success('Se genero con éxito el producto.');
	}

	handleBeforeInitializazingTable() {
		loader.show("Cargando los productos, espere por favor...");
	}
	handleAfterInitializazingTable() {
		loader.hide();
	}
}
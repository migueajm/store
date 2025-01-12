import { DatatableService } from "../utilities/datatable_service.js";
import { loader } from "../utilities/loader_service.js";
import { disbaledElement } from "../utilities/utilities.js";
import { columns } from "./columns.js";

export class Stock{
	constructor(actionBtn){
		this.path = "/app/stocktaking";
		this.datatable = new DatatableService(
			'table#table-product',
			columns,
			this.path + "/stock/all",
			null,
			this.handleBeforeInitializazingTable,
			this.handleAfterInitializazingTable
		);
		
		this.actionButton = actionBtn;
		this.actionButton.addEventListener('click', () => this.redirect({instance: this, e: actionBtn}));
	}

	redirect({instance, e}){
		disbaledElement(e);
		loader.show();
		window.location.href = instance.path + "/admin/index";
	}

	handleBeforeInitializazingTable() {
		loader.show("Cargando los registros, espere por favor...");
	}
	handleAfterInitializazingTable() {
		loader.hide();
	}
}
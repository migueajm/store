import { DatatableService } from "../utilities/datatable_service.js";
import { loader } from "../utilities/loader_service.js";
import { disbaledElement } from "../utilities/utilities.js";
import { columns } from "./columns.js";

export class Stocktaking{
	constructor(actionBtn){
		this.path = "/app/stocktaking";
		this.datatable = new DatatableService(
			'table#table-stocktaking',
			columns,
			this.path + "/all",
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
		window.location.href = instance.path + "/index";
	}

	handleBeforeInitializazingTable() {
		loader.show("Cargando los registros, espere por favor...");
	}
	handleAfterInitializazingTable() {
		loader.hide();
	}
}
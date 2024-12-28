import { DataTableError } from "./error/datatable_error.js";
import { language } from "./language_datatable.js";
import { disbaledElement, fetchService, toast } from "./utilities.js";

export class DatatableService {
    constructor(
        table,
        columns,
        url,
        token = null,
        handleBeforeInitializing = null,
        handleAfterInitializing = null,
        handleUpdate = null,
        hanldeRemove = null
    ) {
        if (!table) {
            throw new DataTableError("Undefined selector or element.");
        }
        this.selector = table;
        if (!Array.isArray(columns) || columns.length === 0) {
            throw new DataTableError("Invalid or empty columns definition.");
        }
        if (typeof url !== "string" || !url.trim()) {
            throw new DataTableError("The URL must be a valid string.");
        }

        this.url = url;
        this.columns = columns;
        this.handleBeforeInitializing = handleBeforeInitializing;
        this.handleAfterInitializing = handleAfterInitializing;
        this.handleUpdate = handleUpdate;
        this.hanldeRemove = hanldeRemove;
        this.datatableInstance = null;
        this.isLoading = false;

        if (token) {
            this.setToken(token);
        }

        this.initialize();
    }

    setToken(token) {
        if (typeof token === "string" && token.trim()) {
            fetchService.setAuthToken(token);
        } else {
            throw new DataTableError("Invalid token.");
        }
    }

    async initialize() {
        if (this.isLoading) {
            return;
        }
        this.isLoading = true;
        try {
            if (typeof this.handleBeforeInitializing === "function") {
                this.handleBeforeInitializing({ instance: this });
            }

            const data = await fetchService.get(this.url);
            this.datatableInstance = $(this.selector).DataTable({
                processing: true,
                data: data.data,
                columns: this.columns,
                language: language,
            });
            Array.from(this.datatableInstance.rows().nodes()).forEach(element => {
                if (typeof this.handleUpdate === 'function') {
                    const btn = element.querySelector('.btn-update');
                    if(btn){
                        btn.addEventListener('click', () => {
                            disbaledElement(btn);
                            this.handleUpdate({ instance: this, data: btn.dataset});
                        });
                    }
                }
                if (typeof this.hanldeRemove === 'function') {
                    const btn = element.querySelector('.btn-remove');
                    if(btn){
                        btn.addEventListener('dblclick', () => {
                            disbaledElement(btn);
                            this.hanldeRemove({ instance: this, id: btn.dataset.id});
                        });
                    }
                }
            });
            if (typeof this.handleAfterInitializing === "function") {
                setTimeout(() => this.handleAfterInitializing({ instance: this }), 1000);
            }
        } catch (error) {
            console.error("Error initializing the DataTable:", error);
            throw new DataTableError("Failed to initialize the DataTable.");
        } finally {
            this.isLoading = false;
        }
    }

    async reload() {
        if (this.datatableInstance) {
            this.destroy();
            await this.initialize();
        }
    }

    destroy() {
        if (this.datatableInstance) {
            this.datatableInstance.clear();
            this.datatableInstance.destroy();
        }
    }
}
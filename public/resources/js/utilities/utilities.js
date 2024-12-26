import { loader } from "./loader_service.js";
import { toast } from "./toast_service.js";
import { FetchService } from "./fetch_service.js";
import { FormError } from "./error/form_error.js";
import { FormErrorManager } from "./form_error_manager.js";
import { HtmlFormElementExpectedError } from "./error/html_form_element_expected_error.js";
import { ObjectExpectedError } from "./error/object_expected_error.js";
/**
 * Maneja un error, ya sea del tipo Error o alguna subclase.
 * @param {Error} error El error que se va a manejar.
 * @param {HTMLFormElement} form El formulario que se manejara para mostrar los errores.
 * @returns {void}
 */
const handleFetchError = (error, form = null) => {
	loader.hide();
	const delay = 1000 * 20;
	if (form instanceof HTMLFormElement && error instanceof FormError) {
		const formErrorManager = new FormErrorManager(form);
		Object.keys(error.formError).forEach(key => {
			formErrorManager.showError(key, error.formError[key]);
		});
	}
	if (error.statusCode < 500) {
		return toast.warning(error.message, delay);
	}
	toast.error(error.message, delay);
}

/**
 * Convierte los valores de un FormData en un json, si los name's vienen entre "[]" solo toma el valor dentro de los "[]".
 * @param {FormData} formData 
 * @returns 
 */
const formDataToJson = formData => {
	const json = {};
	for (const [key, value] of formData.entries()) {
		const match = key.match(/\[(.*?)\]/);
		if (match) {
			json[match[1]] = value;
		} else {
			json[key] = value;
		}
	}
	return json;
}

/**
 * 
 * @param {HTMLElement} element Elemento a desabilitar 2 segundos.
 */
const disbaledElement = element => {
	if (element instanceof HTMLElement) {
		element.disabled = true;
		setTimeout(() => element.disabled = false, 2000);
	}
}

const createModal = ({ id = "modal-default", size = "md", title = "Modal Title", body = "", closeText = "Cerrar", actionText = "Confirmar" }) => {
	return new Promise((resolve) => {
		const modalHtml = `
					<div id="${id}" class="modal fade modal-${size}" tabindex="-1" role="dialog">
							<div class="modal-dialog">
									<div class="modal-content">
											<div class="modal-header">
													<h5 class="modal-title">${title}</h5>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
											</div>
											<div class="modal-body">
													${body}
											</div>
											<div class="modal-footer">
													<button id="${id}-cancel" type="button" class="btn btn-secondary" data-bs-dismiss="modal">${closeText}</button>
													<button id="${id}-confirm" type="button" class="btn btn-primary">${actionText}</button>
											</div>
									</div>
							</div>
					</div>
			`;
		document.body.insertAdjacentHTML("beforeend", modalHtml);
		const modalElement = document.getElementById(id);
		const bootstrapModal = new bootstrap.Modal(modalElement);
		bootstrapModal.show();
		const cancelButton = document.getElementById(`${id}-cancel`);
		const confirmButton = document.getElementById(`${id}-confirm`);
		cancelButton.addEventListener("click", () => {
			resolve(false);
			bootstrapModal.hide();
		});

		confirmButton.addEventListener("click", () => {
			resolve(true);
			bootstrapModal.hide();
		});
		modalElement.addEventListener("hidden.bs.modal", () => {
			modalElement.remove();
		});
	});
}

const fetchService = new FetchService(window.origin)
fetchService.setErrorFunction(handleFetchError);
export {
	toast,
	fetchService,
	createModal,
	disbaledElement,
	formDataToJson,
	handleFetchError
};
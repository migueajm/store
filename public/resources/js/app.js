import { Authentication } from './security/authentication.js';
import { ToastService } from './utilities/toast_service.js';
import { FetchService } from "./utilities/fetch_service.js";
import { DateFormatter } from './utilities/date_formatter.js';
import { FormErrorManager } from './utilities/form_error_manager.js';
import { FormError } from './utilities/error/form_error.js';
import { Dashboard } from './dashboard/dashboard.js';

function routeHandler() {
  const path = window.location.pathname;
	if (path === "/sign-in") {
		new Authentication(
			document.querySelector('form[name=sign_in]'),
			document.querySelector('form[name=sign_out]')
		);
		return;
	}
  if (path.includes("/app/dashboard")) {
    new Dashboard();
		return;
  }
  console.warn("Undefined url: " + path);
}

routeHandler();
window.addEventListener('popstate', routeHandler);

const dateFormatter = new DateFormatter('en-US');
const toast = new ToastService();
const fetchService = new FetchService(window.origin);

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
 * Maneja un error, ya sea del tipo Error o alguna subclase.
 * @param {Error} error El error que se va a manejar.
 * @param {HTMLFormElement} form El formulario que se manejara para mostrar los errores.
 * @returns {void}
 */
const handleErrorFetch = (error, form = null) => {
	//loader.hide();
	const delay = 1000 * 20;
	if(form instanceof HTMLFormElement && error instanceof FormError){
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
 * 
 * @param {HTMLElement} element Elemento a desabilitar 2 segundos.
 */
const disbaledElement = element => {
	if(element instanceof HTMLElement) {
		element.disabled = true;
		setTimeout(() => element.disabled = false, 2000);
	}
}

fetchService.setErrorFunction(handleErrorFetch);
export {
	toast,
	fetchService,
	dateFormatter,
	disbaledElement,
	formDataToJson
};

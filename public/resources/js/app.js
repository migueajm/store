import { SignIn } from './security/signIn.js';
import { ToastService } from './utilities/toast.js';
import { FetchService } from "./utilities/fetch_service.js";
import { DateFormatter } from './utilities/date_formatter.js';
import { FormErrorManager } from './utilities/form_error_manager.js';

const dateFormatter = new DateFormatter('en-US');
new SignIn(document.querySelector('form[name=sign_in]'));

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
	if(form instanceof HTMLFormElement && error.hasOwnProperty('inputs')){
		const formErrorManager = new FormErrorManager(form);
		error.inputs.forEach(element => {
			formErrorManager.showError(element.key, element.error);
		});
	}
	if (error.statusCode < 500) {
		return toast.warning(error.message, delay);
	}
	toast.error(error.message, delay);
}

fetchService.setErrorFunction(handleErrorFetch);

export {toast, fetchService, formDataToJson, dateFormatter};

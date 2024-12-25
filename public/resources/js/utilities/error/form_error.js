export class FormError extends Error {
	/**
	 * Crea un error personalizado para formularios.
	 * 
	 * @param {object} formError - El nombre del campo que tiene el error.
	 * @param {string} message - El mensaje de error a mostrar.
	 */
	constructor(formError, message, statusCode = 400, statusText = 'Bad request.') {
			super(message);
			this.name = 'FormError';
			this.formError = formError;
			this.statusCode = statusCode;
			this.statusText = statusText;
	}
}
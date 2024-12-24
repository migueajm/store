export class FormError extends Error {
	/**
	 * Crea un error personalizado para formularios.
	 * 
	 * @param {string} fieldName - El nombre del campo que tiene el error.
	 * @param {string} message - El mensaje de error a mostrar.
	 */
	constructor(fieldName, message) {
			super(message);
			this.name = 'FormError';
			this.fieldName = fieldName;
	}
}
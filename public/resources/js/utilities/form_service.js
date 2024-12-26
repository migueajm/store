export class FormService {
	constructor() { }

	/**
	 * Se encarga de llenar un formulario de información.
	 * @param {HTMLFormElement} form Formulario a minipular
	 * @param {object} entity Objecto con los valores a asignar al formulario
	 */
	static setData(form, entity) {
		if (!form instanceof HTMLFormElement) {
			throw new HtmlFormElementExpectedError(typeof form);
		}
		if (typeof entity != "object") {
			throw new ObjectExpectedError(typeof entity);
		}
		Object.keys(entity).forEach(key => {
			const element = document.querySelector(`#${form.name}_${key}`);
			if (!element) return;
			element.value = entity[key];
		});
	};

	/**
	 * Se encarga de obtener toda la información del formulario.
	 * @param {HTMLFormElement} form Formulario a minipular
	 * @param {bool} asJson True si se requiere un objecto; false para un FormData
	 * @param {bool} isSymfonyForm false se encarga de dejar solo el identificador.
	 */
	static getData(form, asJson = false, isSymfonyForm = true) {
		const formData = new FormData(form);
		const formId = form.id;
		if (!asJson) return formData;
		const jsonObject = {};
		formData.forEach((value, key) => {
			if(!isSymfonyForm){
				key = FormService.extractText(key);
			}
			if (jsonObject.hasOwnProperty(key)) {
				if (!Array.isArray(jsonObject[key])) {
					jsonObject[key] = [jsonObject[key]];
				}
				jsonObject[key].push(value);
			} else {
				if(!value){
					const element = document.querySelector(`#${formId}_${key}`);
					if(element && element.type === 'datetime-local'){
						value = element.getAttribute('value');
					}
				}
				jsonObject[key] = value;
			}
		});
		if(form.dataset.hasOwnProperty('id')){
			jsonObject.id = form.dataset.id;
		}
		return jsonObject;
	}

	static extractText(str) {
		const matches = str.match(/\[(.*?)\]/);
		return matches ? matches[1] : str; 
	}
}
import { router } from './utilities/router.js';
import { createIconShowPassword } from './utilities/utilities.js';
document.addEventListener('DOMContentLoaded', () => {
	router.routeHandler();
	window.addEventListener('popstate', router.routeHandler);
	[
		...document.querySelectorAll('input'),
		...document.querySelectorAll('textarea'),
		...document.querySelectorAll('select'),
	].forEach(element => {
		const label =  element.parentNode.querySelector(`label[for=${element.id}]`);
		if(!label || !element.required) {
			createIconShowPassword(element);
			return;
		}
		const content = label.innerHTML;
		label.innerHTML = '<span class="text-danger">* </span>' + content;
		createIconShowPassword(element);
	})
});
import { router } from './utilities/router.js';
document.addEventListener('DOMContentLoaded', () => {
	router.routeHandler();
	window.addEventListener('popstate', router.routeHandler);
	[
		...document.querySelectorAll('input'),
		...document.querySelectorAll('textarea'),
		...document.querySelectorAll('select'),
	].forEach(element => {
		const label =  element.parentNode.querySelector(`label[for=${element.id}]`);
		if(!label || !element.required) return;
		const content = label.innerHTML;
		label.innerHTML = '<span class="text-danger">* </span>' + content;
	})
});
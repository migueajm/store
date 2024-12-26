import { router } from './utilities/router.js';
document.addEventListener('DOMContentLoaded', () => {
	router.routeHandler();
	window.addEventListener('popstate', router.routeHandler);
});
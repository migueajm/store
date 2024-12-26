class Router
{
	constructor(){}

	routeHandler() {
		const path = window.location.pathname;
		if (path === "/sign-in") {
			import('../security/authentication.js')
				.then(({ Authentication }) => {
					new Authentication(
						document.querySelector('form[name=sign_in]'),
						document.querySelector('form[name=sign_out]')
					);
				})
				.catch((error) => {
					console.error("Error loading Authentication module:", error);
				});
			return;
		}
	
		if (path.includes("/app/dashboard")) {
			import('../dashboard/dashboard.js')
				.then(({ Dashboard }) => {
					new Dashboard();
				})
				.catch((error) => {
					console.error("Error loading Dashboard module:", error);
				});
			return;
		}
	
		if (path.includes("/admin/product")) {
			const key = "product";
			import(`../${key}/${key}.js`)
				.then(({ Product }) => {
					new Product(
						document.getElementById('btn-save-'+key),
						document.getElementById('btn-new-'+key),
						document.querySelector(`form[name=${key}]`)
					);
				})
				.catch((error) => {
					console.error("Error loading Dashboard module:", error);
				});
			return;
		}
		if (path.includes("/admin/category")) {
			const key = "category";
			import(`../${key}/${key}.js`)
				.then(({ Category }) => {
					new Category(
						document.getElementById('btn-save-'+key),
						document.getElementById('btn-new-'+key),
						document.querySelector(`form[name=${key}]`)
					);
				})
				.catch((error) => {
					console.error("Error loading Dashboard module:", error);
				});
			return;
		}
		if (path.includes("/admin/user")) {
			const key = "user";
			import(`../${key}/${key}.js`)
				.then(({ User }) => {
					new User(
						document.getElementById('btn-save-'+key),
						document.getElementById('btn-new-'+key),
						document.querySelector(`form[name=${key}]`)
					);
				})
				.catch((error) => {
					console.error("Error loading Dashboard module:", error);
				});
			return;
		}
		console.warn("Undefined url: " + path);
	}
}
export const router = new Router();
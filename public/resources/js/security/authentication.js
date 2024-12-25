import {fetchService, formDataToJson, disbaledElement}  from "../app.js";
export class Authentication {
	static keys = {
		tokenSignOut: "migueajmstore-token-sign-out"
	};
	constructor(formSignIn = null, btnSignOut = null) {
		this.formSignIn = formSignIn;
		this.btnSignOut = btnSignOut;
		this.signIn = this.signIn.bind(this);
		this.signOut = this.signOut.bind(this);
		this.formSignIn?.addEventListener('submit', this.signIn);
		this.btnSignOut?.addEventListener('click', this.signOut);
	}

	async signIn(event) {
		event.preventDefault();
		disbaledElement(this.formSignIn['btn-sign-in']);
		fetchService.setForm(this.formSignIn);
		const formData = new FormData(this.formSignIn);
		const body = formDataToJson(formData);
		body.token = btoa(JSON.stringify(body));
		await fetchService.post("/sign-in", body);
	}

	async signOut(event) {
		event.preventDefault();
		disbaledElement(event.target);
		const token = localStorage.getItem(Authentication.keys.tokenSignOut);
		fetchService.setAuthToken(token);
		await fetchService.get('/sign-out');
	}
}

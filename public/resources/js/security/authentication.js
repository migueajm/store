import {formDataToJson, disbaledElement, handleFetchError, fetchService}  from "../utilities/utilities.js";
export class Authentication {
	static keys = {
		tokenSignOut: "migueajmstore-token-sign-out"
	};
	constructor(signInForm = null, signOutbtn = null) {
		this.signInForm = signInForm;
		this.signOutbtn = signOutbtn;
		this.signIn = this.signIn.bind(this);
		this.signOut = this.signOut.bind(this);
		this.signInForm?.addEventListener('submit', this.signIn);
		this.signOutbtn?.addEventListener('click', this.signOut);
		fetchService.setErrorFunction(handleFetchError)
	}

	async signIn(event) {
		event.preventDefault();
		disbaledElement(this.signInForm['btn-sign-in']);
		fetchService.setForm(this.signInForm);
		const formData = new FormData(this.signInForm);
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

import * as app from "../app.js";
export class SignIn {
	constructor(formElement) {
		this.formElement = formElement;
		this.submit = this.submit.bind(this);
		this.formElement.addEventListener('submit', this.submit);
	}

	async submit(event) {
		event.preventDefault();
		app.fetchService.setForm(this.formElement);
		const formData = new FormData(this.formElement);
		const body = app.formDataToJson(formData);
		await app.fetchService.post("/sign-in", {token: btoa(JSON.stringify(body))});
	}
}

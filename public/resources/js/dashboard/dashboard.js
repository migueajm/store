import { Authentication } from "../security/authentication.js";

export class Dashboard
{
	constructor(){
		const params = new URLSearchParams(window.location.search);
		const token = params.get('token');
		if(token){
			localStorage.setItem(Authentication.keys.tokenSignOut, token);
		}
	}
}
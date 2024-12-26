import { DateFormatter } from "./date_formatter.js";

class ToastService {
	color = {
		success: "#198754",
		warning: '#ffc107',
		danger: '#dc3545'
	};
  constructor() {
    this.toastContainer = document.getElementById('toast-container') || document.createElement('div');
    this.toastContainer.id = 'toast-container';
    this.toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
    document.body.appendChild(this.toastContainer);
    this.formatter = new DateFormatter();
  }

  /**
   * Muestra un toast de éxito.
   * @param {string} message - El mensaje de éxito.
   * @param {number} [delay=3000] - Tiempo en milisegundos antes de que el toast se oculte (opcional).
   */
  success(message, delay = 3000) {
    this.showToast(message, 'success', delay);
  }

  /**
   * Muestra un toast de error.
   * @param {string} message - El mensaje de error.
   * @param {number} [delay=3000] - Tiempo en milisegundos antes de que el toast se oculte (opcional).
   */
  error(message, delay = 3000) {
    this.showToast(message, 'danger', delay);
  }

  /**
   * Muestra un toast de advertencia.
   * @param {string} message - El mensaje de advertencia.
   * @param {number} [delay=3000] - Tiempo en milisegundos antes de que el toast se oculte (opcional).
   */
  warning(message, delay = 3000) {
    this.showToast(message, 'warning', delay);
  }

  /**
   * Muestra un Toast con el tipo proporcionado.
   * @param {string} message - El mensaje que se mostrará en el Toast.
   * @param {string} type - El tipo de toast ('success', 'danger', 'warning').
   * @param {number} delay - Tiempo en milisegundos antes de que el toast se oculte.
   */
  showToast(message, type, delay) {
		const toast = document.createElement('div');
    toast.className = `toast`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    toast.innerHTML = `
		<div class="toast-header">
		<svg class="bd-placeholder-img rounded me-2 bg-${type}" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="${this.color[type]}"></rect></svg>
		<strong class="me-auto">${type.toUpperCase()}</strong>
		<small class="text-muted">${this.formatter.getHour()}</small>
		<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
		</div>
		<div class="toast-body">
		${message}
		</div>
    `;
    
    this.toastContainer.appendChild(toast);
    const toastInstance = new bootstrap.Toast(toast, {
      autohide: true,
      delay: delay
    });
    toastInstance.show();
		setTimeout(() => toast.remove(), delay);
  }
}

export const toast = new ToastService();

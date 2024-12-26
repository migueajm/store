class LoaderService {
  constructor() {
    this.loaderElementId = 'loader-element';
  }

  show(message = "Cargando, por favor espere...") {
    const  overlayElement = document.createElement('div');
    overlayElement.classList.add('position-fixed', 'top-0', 'start-0', 'w-100', 'h-100', 'bg-dark', 'opacity-75', 'd-flex', 'justify-content-center', 'align-items-center');
    overlayElement.id = this.overlayElementId;
    document.body.appendChild(overlayElement);

    const loaderElement = document.createElement('div');
    loaderElement.classList.add('text-center', 'bg-white', 'p-4', 'rounded-3', 'shadow');

    const spinner = document.createElement('div');
    spinner.classList.add('spinner-border', 'text-success');
    spinner.setAttribute('role', 'status');
    const span = document.createElement('span');
    span.classList.add('visually-hidden');
    span.innerText = 'Loading...';
    spinner.appendChild(span);

    const loaderMessage = document.createElement('p');
    loaderMessage.classList.add('mt-3');
    loaderMessage.innerText = message;

    loaderElement.appendChild(spinner);
    loaderElement.appendChild(loaderMessage);

    overlayElement.appendChild(loaderElement);

    document.body.style.pointerEvents = 'none';
    document.querySelector('.container-fluid.vh-100')?.classList.add('opacity-25');
    setTimeout(() => {
      this.hide();
    }, 120000);
  }

  hide() {
    const overlayElement = document.getElementById(this.overlayElementId);
    if (overlayElement) {
      overlayElement.remove();
    }
    document.body.style.pointerEvents = 'auto';
    document.querySelector('.container-fluid.vh-100')?.classList.remove('opacity-25')
  }
}
export const loader = new LoaderService();

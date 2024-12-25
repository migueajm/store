export class FetchServiceError extends Error {
  constructor(message, statusCode = null, statusText = '') {
    super(message);
    this.name = 'FetchServiceError';
    this.statusCode = statusCode;
    this.statusText = statusText;
    this.stack = (new Error()).stack;
  }

  setFormE
}
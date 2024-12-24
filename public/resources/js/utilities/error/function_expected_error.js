export class FunctionExpectedError extends TypeError {
  constructor(received) {
    super(`Expected a function, but received ${typeof received}.`);
    this.name = 'FunctionExpectedError';
  }
}
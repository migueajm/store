export class ObjectExpectedError extends TypeError {
  constructor(received) {
    super(`Expected a Object, but received ${typeof received}.`);
    this.name = 'FunctionExpectedError';
  }
}
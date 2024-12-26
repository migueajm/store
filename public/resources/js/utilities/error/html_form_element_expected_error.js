export class HtmlFormElementExpectedError extends TypeError {
  constructor(received) {
    super(`Expected a HTMLFormElement, but received ${typeof received}.`);
    this.name = 'FunctionExpectedError';
  }
}
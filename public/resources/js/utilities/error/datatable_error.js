export class DataTableError extends Error {
  constructor(message) {
    super(message);
    this.name = "DataTableError";
    this.stack = (new Error()).stack;
  }
}
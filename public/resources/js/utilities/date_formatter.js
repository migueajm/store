export class DateFormatter {
  constructor(locale = 'en-US') {
    this.locale = locale;
    this.options = {
      date: { day: '2-digit', month: '2-digit', year: 'numeric' },
      hour: { hour: '2-digit', minute: '2-digit', second: '2-digit' }
    };
  }

  getHour(date = new Date()) {
    return date.toLocaleTimeString(this.locale, this.options.hour);
  }

  getDate(date = new Date()) {
    return date.toLocaleDateString(this.locale, this.options.date);
  }

  getFullDate(locale = 'en-US') {
    return `${this.getDate()} ${this.getHour}`;
  }

  static getFormattedDateISO() {
    const today = new Date();
    const datePart = today.toISOString().split('T')[0];
    const formattedDate = `${datePart}T12:00:00`;
    return formattedDate;
  }
}
export class DateISO {
    date: Date;
    constructor(value?: number | string | Date, month?: number, date?: number, hours?: number, minutes?: number, seconds?: number, ms?: number) {
        if(month) {
            let year:number = <number>value;
            this.date = new Date(year, month, date, hours, minutes, seconds, ms);
        } else if(value) {
            this.date = new Date(value);
        } else {
            this.date = new Date();
        }
    }

    toString() {
        return this.date.toISOString();
    }
}
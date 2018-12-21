import {Component} from "@angular/core";
import {FormControl, FormGroup} from "@angular/forms";
import {DateISO} from "../../Entity/ISODate";

@Component({
    templateUrl: "./template.pug",
    styleUrls: ["./style.shadow.scss"]
})

export class MarketRoute {
    public form = new FormGroup({
        dateFrom: new FormControl(), // formatDate(new Date(), 'yyyy-MM-dd', this.locale)
        dateTo: new FormControl(),
    },
        // null,
        //()=> of(null).pipe(filter(() => !this.isPending))
    );

    public formatDateISO(value) {
        if (value) {
            return new DateISO(value).toString();
        }
    }
    
}
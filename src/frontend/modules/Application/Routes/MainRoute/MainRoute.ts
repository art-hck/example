import {Component} from "@angular/core";
import {PlayerRESTService} from "../../../Player/Service/PlayerRESTService";
import {DateISO} from "../../Entity/ISODate";

@Component({
    templateUrl: "./template.pug",
})

export class MainRoute {
    
    constructor(private playerRESTService: PlayerRESTService ) {
        playerRESTService.filter({
            dateFrom: new DateISO('2003-09-16'),
            dateTo: new DateISO('2003-09-16'),
            limit: 10
        })
        .subscribe((r) => {
            console.log(r);
        });
    }
}
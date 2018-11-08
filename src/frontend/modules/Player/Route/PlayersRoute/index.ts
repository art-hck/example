import {Component, Inject, LOCALE_ID} from "@angular/core";
import {formatDate} from "@angular/common";
import {FormControl, FormGroup} from "@angular/forms";
import {Router} from "@angular/router";
import {DateISO} from "../../../Application/Entity/ISODate";
import {Device} from "../../../Application/Service/Device";
import {PlayerFilterRequest} from "../../Http/PlayerFilterRequest";
import {debounceTime} from "rxjs/operators";

@Component({
    selector: "player-filter-form",
    templateUrl: "./template.pug",
    styleUrls: ["./style.shadow.scss"]
})

export class PlayersRoute {
    public device = Device;
    public isLoading: boolean = false;
    public isFilterActive: boolean = true;
    // public form: FormGroup = new FormGroup({
    //     dateFrom: new FormControl(null),
    //     dateTo: new FormControl(formatDate(new Date(), 'yyyy-MM-dd', this.locale)),
    //     leagueId: new FormControl(""),
    //     teamId: new FormControl(""),
    //     minGoals: new FormControl(""),
    //     maxGoals: new FormControl(""),
    //     minCards: new FormControl(""),
    //     maxCards: new FormControl(""),
    //     cardsType: new FormControl(""),
    //     minPlayTime: new FormControl(""),
    //     maxPlayTime: new FormControl(""),
    //     orderBy: new FormControl(""),
    //     orderDirection: new FormControl(""),
    //     offset: new FormControl(""),
    //     limit: new FormControl(""),
    // });

    form = new FormGroup({
        dateFrom: new FormControl(""), // formatDate(new Date(), 'yyyy-MM-dd', this.locale)
        dateTo: new FormControl(""),
        age: new FormControl([15, 48]),
        teamId: new FormControl(""),
        positionId: new FormControl(""),
        nationalityId: new FormControl(""),
        orderBy: new FormControl(""),
    });

    constructor(
        private router: Router,
        @Inject(LOCALE_ID) private locale: string,
    ) {
        this.form
            .valueChanges
            .pipe(debounceTime(400))
            .subscribe(() => this.submit())
        ;
    }

    submit() {
        
        let request: PlayerFilterRequest = this.form.value;
        
        request.minAge = this.form.value.age[0];
        request.maxAge = this.form.value.age[1];
        delete request.age;
        
        for (let key in request) {
            if (request.hasOwnProperty(key) && (request[key] === "")) {
                delete request[key];
            }
        }
        this.isLoading = true;
        this.isFilterActive = false;

        // console.log(request);
        // this.isLoading = false
        // return; 
        
        this.router
            .navigate(
                ['players', 'filter'],
                {queryParams: request}
            )
            .then(() => this.isLoading = false)
        ;
    }
    
    public formatDateISO(value) {
        if (value) {
            return new DateISO(value).toString();
        }
    }
}
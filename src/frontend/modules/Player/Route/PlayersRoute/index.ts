import {Component, Inject, LOCALE_ID} from "@angular/core";
import {formatDate} from "@angular/common";
import {FormControl, FormGroup} from "@angular/forms";
import {Router} from "@angular/router";
import {DateISO} from "../../../Application/Entity/ISODate";

@Component({
    selector: "player-filter-form",
    templateUrl: "./template.pug",
    styleUrls: ["./style.shadow.scss"]
})

export class PlayersRoute {
    public isLoading: boolean = false;
    public isFilterActive: boolean = true;
    public form: FormGroup = new FormGroup({
        dateFrom: new FormControl(formatDate(new Date(), 'yyyy-MM-dd', this.locale)),
        dateTo: new FormControl(formatDate(new Date(), 'yyyy-MM-dd', this.locale)),
        leagueId: new FormControl(""),
        teamId: new FormControl(""),
        minGoals: new FormControl(""),
        maxGoals: new FormControl(""),
        minCards: new FormControl(""),
        maxCards: new FormControl(""),
        cardsType: new FormControl(""),
        minPlayTime: new FormControl(""),
        maxPlayTime: new FormControl(""),
        orderBy: new FormControl(""),
        orderDirection: new FormControl(""),
        offset: new FormControl(""),
        limit: new FormControl(""),
    });

    constructor(
        private router: Router,
        @Inject(LOCALE_ID) private locale: string
    ) {}

    submit() {
        let request = this.form.value;
        for (let key in request) {
            if (request.hasOwnProperty(key) && request[key] === "") {
                delete request[key];
            }
        }
        this.isLoading = true;
        this.isFilterActive = false;

        this.router
            .navigate(
                ['players', 'filter'],
                {queryParams: request}
            )
            .then(() => this.isLoading = false)
        ;
    }

    onChange(value) {
        if (value) {
            this.form.get("dateFrom").setValue(new DateISO(value) + "");
        }
    }

    toDateISO(value) {
        return new DateISO(value);
    }
}
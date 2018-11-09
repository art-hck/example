import {Component, Inject, LOCALE_ID} from "@angular/core";
import {formatDate} from "@angular/common";
import {FormControl, FormGroup, ValidationErrors, Validators} from "@angular/forms";
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
    public playerRoles = [
        "goalkeeper",
        "defender",
        "left back",
        "centre back",
        "right back",
        "defensive midfield",
        "midfielder",
        "attacking midfield",
        "central midfield",
        "left midfield",
        "right midfield",
        "left wing",
        "centre forward",
        "forward",
        "striker",
        "secondary striker",
        "right wing",
        "sweeper",
    ];
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

    public form = new FormGroup({
        dateFrom: new FormControl(""), // formatDate(new Date(), 'yyyy-MM-dd', this.locale)
        dateTo: new FormControl(""),
        age: new FormControl([15, 48]),
        teamName: new FormControl("", Validators.minLength(3)),
        role: new FormControl("", ((role: FormControl) => {
            if (role.value &&   !~this.playerRoles.indexOf(role.value)) {
                return <ValidationErrors>{invalid_role: true};
            }
        })),
        nationalityId: new FormControl({value:"", disabled: true}),
        orderBy: new FormControl(""),
    });

    constructor(
        private router: Router,
        @Inject(LOCALE_ID) private locale: string,
    ) {
         this.form
             .valueChanges
             .pipe(debounceTime(1000))
             .subscribe(() => {
                 if(this.form.valid)
                    this.submit()
             })
         ;
    }

    public resetIfChecked(e) {
        if(this.form.get('orderBy').value===e.target.value){
            this.form.get('orderBy').reset();
        }
    }
    
    public submit() {
        
        let request: PlayerFilterRequest = {...this.form.value};
        console.log(this.form.value);
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
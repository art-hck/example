import {Component, Inject, LOCALE_ID} from "@angular/core";
import {FormControl, FormGroup, ValidationErrors, Validators} from "@angular/forms";
import {ActivatedRoute, Router} from "@angular/router";
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
        "goalkeeper", "defender", "left back",
        "centre back", "right back", "defensive midfield",
        "midfielder", "attacking midfield", "central midfield",
        "left midfield", "right midfield", "left wing",
        "centre forward", "forward", "striker",
        "secondary striker", "right wing", "sweeper",
    ];
    public minAge = 1;
    public maxAge = 64;

    public form = new FormGroup({
        dateFrom: new FormControl(""), // formatDate(new Date(), 'yyyy-MM-dd', this.locale)
        dateTo: new FormControl(""),
        age: new FormControl([this.minAge, this.maxAge]),
        teamName: new FormControl("", Validators.minLength(3)),
        role: new FormControl("", ((role: FormControl) => {
            if (role.value && !~this.playerRoles.indexOf(role.value)) {
                return <ValidationErrors>{invalid_role: true};
            }
        })),
        nationalityId: new FormControl({value:"", disabled: true}),
        orderBy: new FormControl(""),
    });

    constructor(
        private router: Router,
        private route: ActivatedRoute,
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

        this.route.queryParams.subscribe(params => {
            for(let param in params) {
                if(this.form.get(param) ) {
                    try {
                        this.form.get(param).setValue(JSON.parse(params[param]));
                    } catch (e) {
                        this.form.get(param).setValue(params[param]);
                    }
                }
            }
        });
    }

    public resetIfChecked(value) {
        if(this.form.get('orderBy').value===value){
            this.form.get('orderBy').reset();
        }
    }
    
    public submit() {
        
        let request: PlayerFilterRequest = {...this.form.value};

        if([this.minAge, this.maxAge] != this.form.value.age)
            request.age = JSON.stringify(request.age);
        else {
            delete request.age;
        }
        
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
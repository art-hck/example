import {Component, Inject, LOCALE_ID} from "@angular/core";
import {FormControl, FormGroup, ValidationErrors, Validators} from "@angular/forms";
import {ActivatedRoute, Router} from "@angular/router";
import {debounceTime, filter, flatMap, map} from "rxjs/operators";

import {DateISO} from "../../../Application/Entity/ISODate";
import {Device} from "../../../Application/Service/Device";
import {PlayerRoleEnum} from "../../Entity/PlayerRoleEnum";
import {ParamsService} from "../../../Application/Service/ParamsService";
import {PlayerFilterRequest} from "../../Http/PlayerFilterRequest";
import {TeamRESTService} from "../../../Team/Service/TeamRESTService";
import {of} from "rxjs/internal/observable/of";

@Component({
    selector: "player-filter-form",
    templateUrl: "./template.pug",
    styleUrls: ["./style.shadow.scss"]
})

export class PlayersRoute {
    public device = Device;
    public isLoading: boolean = false;
    public isFiltersOpened: boolean = true;
    public playerRoles = Object.values(PlayerRoleEnum);

    public form = new FormGroup({
        age: new FormControl(),
        assists: new FormControl(),
        cards: new FormControl(),
        dateFrom: new FormControl(), // formatDate(new Date(), 'yyyy-MM-dd', this.locale)
        dateTo: new FormControl(),
        goals: new FormControl(),
        height: new FormControl(),
        international: new FormControl(),
        leagueName: new FormControl(null, Validators.minLength(3)),
        teamName: new FormControl(null, Validators.minLength(3)),
        playerName: new FormControl(null, Validators.minLength(3)),
        playTime: new FormControl(),
        role: new FormControl(null, (role: FormControl) => {
            if (role.value && !~this.playerRoles.indexOf(role.value)) {
                return <ValidationErrors>{invalid_role: true};
            }
        }),
        nationalityId: new FormControl({value: "", disabled: true}),
        orderBy: new FormControl(),
        offset: new FormControl(),
    }, 
    null, 
    ()=> of(null).pipe(filter(() => !this.isPending))
    );
    
    public isPending: boolean = false;
    public teamNames = this.form.get("teamName").valueChanges.pipe(
        filter(value => value),
        debounceTime(500),
        flatMap(value => this.teamService.findByname(value))
    );

    constructor(
        private router: Router,
        private route: ActivatedRoute,
        private paramsService: ParamsService,
        private teamService: TeamRESTService,
        @Inject(LOCALE_ID) private locale: string,
    ) {
        this.form.valueChanges.pipe(
            debounceTime(500),
            filter(() => this.form.valid)
        )
        .subscribe(() => this.submit());

        this.route.queryParams
            .pipe(map(params => this.paramsService.parse<PlayerFilterRequest>(params)))
            .subscribe(request => {
                for (let param in this.form.value) {
                    if (this.form.value.hasOwnProperty(param)) {
                        // Если в queryParams есть параметр и он не совпадает со значением формы - присваиваем                    
                        if (request[param] && request[param] != this.form.get(param).value) {
                            this.form.get(param).setValue(request[param]);
                        }
    
                        // Если в queryParams пусто , но в форме значение есть - обнуляем параметр в форме
                        if (!request[param] && this.form.get(param).value) {
                            this.form.get(param).reset();
                        }
                    }
                }
            })
        ;
    }

    public resetIfChecked(field, value) {
        if (this.form.get(field).value === value) {
            this.form.get(field).reset();
        }
    }

    public submit() {
        this.isLoading = true;
        this.router.navigate(
            ['players', 'filter'], 
            {queryParams: this.paramsService.stringify(this.form.value)}
        )
            .then(() => this.isLoading = false)
        ;
    }

    public formatDateISO(value) {
        if (value) {
            return new DateISO(value).toString();
        }
    }
    
    public setPending(isPending: boolean) {
        this.isPending = isPending;
        this.form.patchValue(this.form.value);
    }
}
import {Component, Inject, LOCALE_ID} from "@angular/core";
import {FormControl, FormGroup, ValidationErrors, Validators} from "@angular/forms";
import {ActivatedRoute, Router} from "@angular/router";
import {debounceTime, filter} from "rxjs/operators";

import {DateISO} from "../../../Application/Entity/ISODate";
import {Device} from "../../../Application/Service/Device";
import {PlayerFilterRequest} from "../../Http/PlayerFilterRequest";
import {PlayerRoleEnum} from "../../Entity/PlayerRoleEnum";

@Component({
    selector: "player-filter-form",
    templateUrl: "./template.pug",
    styleUrls: ["./style.shadow.scss"]
})

export class PlayersRoute {
    public device = Device;
    public isLoading: boolean = false;
    public playerRoles = Object.values(PlayerRoleEnum);

    public form = new FormGroup({
        age: new FormControl(),
        assists: new FormControl(),
        cards: new FormControl(),
        dateFrom: new FormControl(), // formatDate(new Date(), 'yyyy-MM-dd', this.locale)
        dateTo: new FormControl(),
        goals: new FormControl(),
        height: new FormControl(),
        international: new FormControl(false),
        leagueName: new FormControl(null, Validators.minLength(3)),
        teamName: new FormControl(null, Validators.minLength(3)),
        playTime: new FormControl(),
        role: new FormControl(null, ((role: FormControl) => {
            if (role.value && !~this.playerRoles.indexOf(role.value)) {
                return <ValidationErrors>{invalid_role: true};
            }
        })),
        nationalityId: new FormControl({value: "", disabled: true}),
        orderBy: new FormControl(),
    });

    constructor(
        private router: Router,
        private route: ActivatedRoute,
        @Inject(LOCALE_ID) private locale: string,
    ) {
        this.form.valueChanges
            .pipe(
                debounceTime(500),
                filter(() => this.form.valid)
            )
            .subscribe(() => this.submit())
        ;

        this.route.queryParams.subscribe(params => {
            for (let param in this.form.value) {
                if (this.form.value.hasOwnProperty(param)) {
                    // Если в queryParams есть параметр и он не совпадает со значением формы - присваиваем                    
                    if (params[param] && params[param] != this.form.get(param).value) {
                        try {
                            this.form.get(param).setValue(JSON.parse(params[param]));
                        } catch (e) {
                            this.form.get(param).setValue(params[param]);
                        }
                    }

                    // Если в queryParams пусто , но в форме значение есть - обнуляем параметр в форме
                    if (!params[param] && this.form.get(param).value) {
                        this.form.get(param).reset();
                    }
                }
            }
        });
    }

    public resetIfChecked(controlName, value) {
        console.log(value);
        if (this.form.get(controlName).value === value) {
            this.form.get(controlName).reset();
        }
    }

    public submit() {
        let request: PlayerFilterRequest = {};

        Object.keys(this.form.controls).forEach(
            k => request[k] = Array.isArray(this.form.value[k]) ? JSON.stringify(this.form.value[k]) : this.form.value[k]
        );

        this.isLoading = true;

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
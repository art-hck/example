import {Component} from "@angular/core";
import {FormControl, FormGroup, Validators} from "@angular/forms";
import {DateISO} from "../../Entity/ISODate";
import {PlayerRESTService} from "../../../Player/Service/PlayerRESTService";
import {TransferRESTService} from "../../../Transfer/Service/TransferRESTService";
import {GameRESTService} from "../../../Game/Service/GameRESTService";
import {debounceTime, filter, map} from "rxjs/operators";
import {ActivatedRoute, Router} from "@angular/router";
import {ParamsService} from "../../Service/ParamsService";
import {PlayerFilterRequest} from "../../../Player/Http/PlayerFilterRequest";

@Component({
    templateUrl: "./template.pug",
    styleUrls: ["./style.shadow.scss"]
})

export class MarketRoute {
    
    public form = new FormGroup({
        dateFrom: new FormControl(null, Validators.required), // formatDate(new Date(), 'yyyy-MM-dd', this.locale)
        dateTo: new FormControl(null, Validators.required),
    });

    get topPlayerGoals$() { return this.playerService.filter(this.mergeRequest({orderBy: 'goals', orderDirection: 'DESC', limit: 5, goals: [0, null]})); }
    get topPlayerAssists$() { return this.playerService.filter(this.mergeRequest({orderBy: 'assists', orderDirection: 'DESC', limit: 5, assists: [0, null]})); }
    get topPlayerViews$() { return this.playerService.filter(this.mergeRequest({orderBy: 'playTime', orderDirection: 'DESC', limit: 5, playTime: [0, null]})); }
    get largestTransfers$() { return this.transfersService.filter(this.mergeRequest({orderBy: 'fee', orderDirection: 'DESC', limit: 5})); }
    get lastestTransfers$() { return this.transfersService.filter(this.mergeRequest({orderBy: 'date', orderDirection: 'DESC', limit: 5})); }
    get lastestGames$() { return this.gamesService.filter(this.mergeRequest({duration: [1, null], orderBy: 'date', orderDirection: 'DESC', limit: 5})); }

    private filterRequest;

    constructor(
        private router: Router,
        private route: ActivatedRoute,
        private paramsService: ParamsService,
        public playerService: PlayerRESTService,
        public transfersService: TransferRESTService,
        public gamesService: GameRESTService
    ) {
        this.form.valueChanges
            .pipe(filter(() => this.form.valid))
            .subscribe(() => this.submit())
        ;

        this.route.queryParams
            .pipe(map(params => this.paramsService.parse(params)))
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
    
    public mergeRequest(request) {
        return {...request, ...this.filterRequest};
    }

    public formatDateISO(value) {
        if (value) {
            return new DateISO(value).toString();
        }
    }
    
    public submit() {
        this.router.navigate(
            ['market'],
            {queryParams: this.paramsService.stringify(this.form.value)}
        );

        this.filterRequest = this.form.value
    }
}``
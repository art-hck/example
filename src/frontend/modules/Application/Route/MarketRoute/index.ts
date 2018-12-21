import {Component} from "@angular/core";
import {FormControl, FormGroup, Validators} from "@angular/forms";
import {DateISO} from "../../Entity/ISODate";
import {PlayerRESTService} from "../../../Player/Service/PlayerRESTService";
import {TransferRESTService} from "../../../Transfer/Service/TransferRESTService";
import {GameRESTService} from "../../../Game/Service/GameRESTService";
import {debounceTime, filter} from "rxjs/operators";

@Component({
    templateUrl: "./template.pug",
    styleUrls: ["./style.shadow.scss"]
})

export class MarketRoute {
    
    public form = new FormGroup({
        dateFrom: new FormControl(null, Validators.required), // formatDate(new Date(), 'yyyy-MM-dd', this.locale)
        dateTo: new FormControl(null, Validators.required),
    });
    private filterRequest;

    constructor(
        public playerService: PlayerRESTService,
        public transfersService: TransferRESTService,
        public gamesService: GameRESTService
    ) {
        this.form.valueChanges
            .pipe(filter(() => this.form.valid))
            .subscribe(() => this.submit())
        ;
    }

    public formatDateISO(value) {
        if (value) {
            return new DateISO(value).toString();
        }
    }
    
    public getTopGoalsPlayers() {
        return this.playerService.filter({...{orderBy: "goals", orderDirection: "DESC", limit: 5, goals: [0, null]}, ...this.filterRequest})
    }

    public getTopAssistsPlayers() {
        return this.playerService.filter({...{orderBy: "assists", orderDirection: "DESC", limit: 5, assists: [0, null]}, ...this.filterRequest})
    }

    public getTopViewsPlayers() {
        return this.playerService.filter({...{orderBy: "playTime", orderDirection: "DESC", limit: 5, playTime: [0, null]}, ...this.filterRequest})
    }
    
    public getLargestTransfers() {
        return this.transfersService.filter({...{orderBy: "fee", orderDirection: "DESC", limit: 5}, ...this.filterRequest});
    }

    public getLastestTransfers() {
        return this.transfersService.filter({...{orderBy: "fee", orderDirection: "DESC", limit: 5}, ...this.filterRequest});
    }

    public getLastestGames() {
        return this.gamesService.getLastGames();
    }
    
    public submit() {
        this.filterRequest = this.form.value
    }
}
import {Component} from "@angular/core";
import {FormControl, FormGroup} from "@angular/forms";
import {DateISO} from "../../Entity/ISODate";
import {PlayerRESTService} from "../../../Player/Service/PlayerRESTService";
import {TransferRESTService} from "../../../Transfer/Service/TransferRESTService";
import {GameRESTService} from "../../../Game/Service/GameRESTService";
import {debounceTime, filter, flatMap, map} from "rxjs/operators";
import {ActivatedRoute, Router} from "@angular/router";
import {ParamsService} from "../../Service/ParamsService";
import {Observable, of} from "rxjs";
import {Team} from "../../../Team/Entity/Team";
import {TeamRESTService} from "../../../Team/Service/TeamRESTService";
import {GroupedLeague, LeagueSeason} from "../../../League/Entity/League";
import {LeagueRESTService} from "../../../League/Service/LeagueRESTService";

@Component({
    templateUrl: "./template.pug",
    styleUrls: ["./style.shadow.scss"]
})

export class MarketRoute {

    public isPending: boolean = false;
    public form = new FormGroup({
        dateFrom: new FormControl(), // formatDate(new Date(), 'yyyy-MM-dd', this.locale)
        dateTo: new FormControl(),
        teamId: new FormControl(),
        teamName: new FormControl(),
        leagueId: new FormControl(),
        leagueName: new FormControl(),
    },
        null,
        ()=> of(null).pipe(filter(() => !this.isPending))
    );

    get topPlayerGoals$() { return this.playerService.filter(this.mergeRequest({orderBy: 'goals', orderDirection: 'DESC', limit: 5, goals: [0, null]})); }
    get topPlayerAssists$() { return this.playerService.filter(this.mergeRequest({orderBy: 'assists', orderDirection: 'DESC', limit: 5, assists: [0, null]})); }
    get topPlayerViews$() { return this.playerService.filter(this.mergeRequest({orderBy: 'views', orderDirection: 'DESC', limit: 5})); }
    get largestTransfers$() { return this.transfersService.filter(this.mergeRequest({orderBy: 'fee', orderDirection: 'DESC', limit: 5})); }
    get lastestTransfers$() { return this.transfersService.filter(this.mergeRequest({orderBy: 'date', orderDirection: 'DESC', limit: 5})); }
    get lastestGames$() { return this.gamesService.filter(this.mergeRequest({duration: [1, null], orderBy: 'date', orderDirection: 'DESC', limit: 5}));}
    
    public teamsAutocomplete: Observable<Team[]> = this.form.get("teamName").valueChanges.pipe(
        debounceTime(500),
        filter(() => this.form.get("teamName").valid),
        filter(value => value),
        flatMap(value => this.teamService.findByName(value))
    );

    public leaguesAutocomplete: Observable<GroupedLeague<LeagueSeason>[]> = this.form.get('leagueName').valueChanges.pipe(
        debounceTime(500),
        filter(value => value),
        filter(() => this.form.get('leagueName').valid),
        flatMap(value => this.leagueService.findByName(value)),
        map(leagues => {
            let groupedLeagues: GroupedLeague<LeagueSeason>[] = [];
            leagues.forEach(league => {
                let groupedLeague: GroupedLeague<LeagueSeason> = groupedLeagues.find(groupLeague => groupLeague.groupBy == league.season);
                if(groupedLeague) {
                    groupedLeague.leagues.push(league)
                } else {
                    groupedLeagues.push({
                        groupBy: league.season,
                        leagues: [league]
                    })
                }
            });

            return groupedLeagues;
        }),
        map(groupedLeagues => groupedLeagues.sort((a, b) => b.groupBy - a.groupBy))
    );
    
    private filterRequest;

    constructor(
        private router: Router,
        private route: ActivatedRoute,
        private paramsService: ParamsService,
        public playerService: PlayerRESTService,
        public transfersService: TransferRESTService,
        public gamesService: GameRESTService,
        public teamService: TeamRESTService,
        public leagueService: LeagueRESTService,
    ) {
        this.form.valueChanges
            .pipe(
                debounceTime(500),
                filter(() => this.form.valid)
            )
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
        let filterRequest = this.form.value;
        
        delete filterRequest.teamName;
        delete filterRequest.leagueName;

        if(!filterRequest.dateFrom || !filterRequest.dateTo) {
            delete filterRequest.dateFrom;
            delete filterRequest.dateTo;
        }

        this.filterRequest = filterRequest;
    }

    public setPending(isPending: boolean) {
        this.isPending = isPending;
        this.form.patchValue(this.form.value);
    }    
}
import {Component} from "@angular/core";
import {ActivatedRoute} from "@angular/router";
import {Player} from "../../Entity/Player";
import {FormControl, FormGroup} from "@angular/forms";
import {PlayerRESTService} from "../../Service/PlayerRESTService";
import {DateISO} from "../../../Application/Entity/ISODate";
import {finalize} from "rxjs/internal/operators";

@Component({
    templateUrl: "./template.pug",
    styleUrls: ["style.shadow.scss"]
})

export class PlayerFilterRoute {

    isLoading: boolean = false;
    public form: FormGroup = new FormGroup({
        dateFrom: new FormControl(new DateISO("2003-09-16").toString()),
        dateTo: new FormControl(new DateISO("2003-09-16").toString()),
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

    public players: Player[];
    constructor(
        private route: ActivatedRoute,
        private playerRESTService: PlayerRESTService
    ) {
        this.route.data.subscribe(data => {
            this.players = data.players;
        });
    }
    
    submit(){
        let request = this.form.value;
        for(let key in request) {
            if(request.hasOwnProperty(key ) && request[key] === null) {
                delete request[key];
            }
        }
        
        this.isLoading = true;
        this.playerRESTService.filter(request)
            .pipe(finalize(()=> this.isLoading = false))
            .subscribe(players => this.players = players);
    }
}
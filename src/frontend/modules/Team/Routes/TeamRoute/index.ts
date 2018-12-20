import {Component, Inject, LOCALE_ID} from "@angular/core";
import {ActivatedRoute} from "@angular/router";
import {Team} from "../../Entity/Team";
import {PlayerFilterResponse} from "../../../Player/Http/PlayerFilterResponse";
import {Game} from "../../../Game/Entity/Game";

@Component({
    templateUrl: "./template.pug",
    styleUrls: ["./style.shadow.scss"]
})

export class TeamRoute {
    public team: Team;
    public playerFilterResponse: PlayerFilterResponse;
    public lastGames: Game[];
    constructor(
        private route: ActivatedRoute,
        @Inject(LOCALE_ID) private locale: string
    ) {
        this.route.data.subscribe(data => {
            this.team = data.team;
            this.playerFilterResponse = data.playerFilterResponse;
            this.lastGames = data.lastGames;
        });
    }    
}
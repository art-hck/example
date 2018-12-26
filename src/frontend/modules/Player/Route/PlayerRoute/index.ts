import {Component, Inject, LOCALE_ID} from "@angular/core";
import {ActivatedRoute} from "@angular/router";
import {Player} from "../../Entity/Player";
import {GameByPlayerResponse} from "../../../Game/Http/GameByPlayerResponse";
import {DateISO} from "../../../Application/Entity/ISODate";

@Component({
    templateUrl: "./template.pug",
    styleUrls: ["./style.shadow.scss"]
})

export class PlayerRoute {

    public player: Player;
    public playerGames: GameByPlayerResponse;
    public playerArray;
    constructor(
        private route: ActivatedRoute,
        @Inject(LOCALE_ID) private locale: string
    ) {
        this.route.data.subscribe(data => {
            this.player = data.player;
            this.playerGames = data.playerGames;
        });
    }

    public formatDateISO(value) {
        if (value) {
            return new DateISO(value).toString();
        }
    }
}
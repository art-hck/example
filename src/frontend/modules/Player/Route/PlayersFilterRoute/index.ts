import {Component} from "@angular/core";
import {ActivatedRoute} from "@angular/router";
import {Player} from "../../Entity/Player";

@Component({
    templateUrl: "./template.pug",
    styleUrls: ["./style.shadow.scss"],
})

export class PlayersFilterRoute {
    public players: Player[];
    constructor(private route: ActivatedRoute) {
        this.route.data
            .subscribe(data => this.players = data.players)
        ;
    }
}
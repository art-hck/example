import {Component} from "@angular/core";
import {ActivatedRoute} from "@angular/router";
import {Player} from "../../Entity/Player";

@Component({
    templateUrl: "./template.pug",
})

export class PlayerRoute {

    public player: Player;
    constructor(
        private route: ActivatedRoute,
    ) {
        this.route.data.subscribe(data => {
            this.player = data.player;
        });
    }
}
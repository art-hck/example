import {Component, Inject, LOCALE_ID} from "@angular/core";
import {ActivatedRoute} from "@angular/router";
import {Player} from "../../Entity/Player";
import {formatDate} from "@angular/common";

@Component({
    templateUrl: "./template.html",
    styleUrls: ["./style.shadow.scss"]
})

export class PlayerRoute {

    public player: Player;
    public playerArray;
    constructor(
        private route: ActivatedRoute,
        @Inject(LOCALE_ID) private locale: string
    ) {
        this.route.data.subscribe(data => {
            this.player = data.player;

            this.playerArray = [
                {"field" : "First name", "value" : this.player.first_name},
                {"field" : "Last name", "value" : this.player.last_name},
                {"field" : "Native name", "value" : this.player.native_name},
                {"field" : "Birthday", "value" : formatDate(this.player.birthday, 'yyyy-MM-dd', this.locale)},
                {"field" : "Birth Place", "value" : this.player.birthPlace},
                {"field" : "Height", "value" : this.player.height + "cm"},
                {"field" : "Number", "value" : this.player.number},
                {"field" : "Contract until", "value" : formatDate(this.player.contract_until, 'yyyy-MM-dd', this.locale) },
                {"field" : "Twitter", "value" : this.player.twitter},
                {"field" : "Facebook", "value" : this.player.facebook},
                {"field" : "Instagram", "value" : this.player.instagram},
                {"field" : "Agents", "value" : this.player.agents}
            ]
                .filter(item => !!item.value)
            ;
        });
    }
}
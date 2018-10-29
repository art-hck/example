import {Component, Inject, LOCALE_ID} from "@angular/core";
import {ActivatedRoute} from "@angular/router";
import {Player} from "../../Entity/Player";
import {FormControl, FormGroup} from "@angular/forms";
import {PlayerRESTService} from "../../Service/PlayerRESTService";
import {DateISO} from "../../../Application/Entity/ISODate";
import {finalize} from "Rxjs/internal/operators";
import {formatDate} from "@angular/common";

@Component({
    templateUrl: "./template.pug",
    styleUrls: ["./style.shadow.scss"],
})

export class PlayerFilterRoute {
    isLoading: boolean = false;
    isFilterActive: boolean = true;
player = {
    "id": 2,
    "tm_id": 73564,
    "first_name": "Jason",
    "last_name": "Steele",
    "native_name": "Jason Sean Steele",
    "alias": "jason-steele",
    "birthday": "1990-08-17T00:00:00+0000",
    "birthPlace": "Newton Aycliffe ",
    "foot": 1,
    "role": {
        "id": 1,
        "name": "goalkeeper"
    },
    "height": 188,
    "number": 1,
    "avatar": "/bilder/spielerfotos/s_73564_641_2009_1.jpg?lm=0",
    "created": {
        "date": "2018-05-29 15:38:52.000000",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "updated": {
        "date": "2018-08-09 10:42:32.000000",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "contract_until": {
        "date": "2021-06-29 22:00:00.000000",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "contract_ext": null,
    "twitter": null,
    "facebook": null,
    "instagram": null,
    "agents": null,
    "in_team": {
        "date": "2017-07-25 22:00:00.000000",
        "timezone_type": 3,
        "timezone": "UTC"
    },
    "country": null,
    "team": {
        "id": 1,
        "name": "Sunderland AFC",
        "alias": "sunderland-afc",
        "preview": "/images/wappen/head/289.png?lm=1485645633",
        "created": {
            "date": "2018-05-29 01:16:24.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        },
        "updated": {
            "date": "2018-06-08 10:49:43.000000",
            "timezone_type": 3,
            "timezone": "UTC"
        },
        "tm_id": 289,
        "country": null,
        "players": {}
    },
    "cards": {},
    "goals": {},
    "assists": {}
};
    public form: FormGroup = new FormGroup({
        dateFrom: new FormControl(formatDate(new Date(), 'yyyy-MM-dd', this.locale)),
        dateTo: new FormControl(formatDate(new Date(), 'yyyy-MM-dd', this.locale)),
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
        private playerRESTService: PlayerRESTService,
        @Inject(LOCALE_ID) private locale: string
    ) {
        this.route.data.subscribe(data => {
            this.players = data.players;
        });
    }
    
    submit(){
        let request = this.form.value;
        for(let key in request) {
            if(request.hasOwnProperty(key) && request[key] === null) {
                delete request[key];
            }
        }
        
        this.isLoading = true;
        this.isFilterActive = false;
        this.players = [];
        this.playerRESTService.filter(request)
            .pipe(finalize(()=> this.isLoading = false))
            .subscribe(players => this.players = players);
    }

    onChange(value) {
        this.form.get("dateFrom").setValue(new DateISO(value)+"");
    }
    
    toDateISO(value) {
        return new DateISO(value);
    }
}
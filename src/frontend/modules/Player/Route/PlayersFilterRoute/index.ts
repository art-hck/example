import {Component, ElementRef, HostListener, ViewChild} from "@angular/core";
import {ActivatedRoute, Router} from "@angular/router";
import {PlatformLocation} from "@angular/common";

import {Player} from "../../Entity/Player";
import {PlayerRESTService} from "../../Service/PlayerRESTService";
import {PlayerFilterRequest} from "../../Http/PlayerFilterRequest";
import {ParamsService} from "../../../Application/Service/ParamsService";
import {PlatformService} from "../../../Application/Service/PlatformService";
import {Throttle} from "../../../Application/Decorator/Throttle";

@Component({
    templateUrl: "./template.pug",
    styleUrls: ["./style.shadow.scss"],
})

export class PlayersFilterRoute {
    public players: Player[];
    private request = this.paramsService.parse<PlayerFilterRequest>(this.route.snapshot.queryParams);
    private loading: boolean = false;
    private offsetScrollMarkers: { offset: number, scroll: number }[] = [{offset: this.request.offset || 0, scroll: 0}];

    @ViewChild("playersEl") playersEl: ElementRef;

    constructor(
        private route: ActivatedRoute, 
        private playerService: PlayerRESTService,
        private platformLocation: PlatformLocation,
        private router: Router,
        private paramsService: ParamsService, 
        private pl: PlatformService) 
    {
        this.route.data
            .subscribe(data => this.players = data.players)
        ;
    }

    @HostListener('window:scroll')
    @Throttle(300)
    onScroll() {
        if(this.pl.isPlatformServer()) return;
        
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const documentHeight = document.documentElement.scrollHeight;
        const screenHeight = document.documentElement.clientHeight;
        
        if(scrollTop + (screenHeight*3) >= documentHeight - screenHeight && !this.loading) {
            this.loading = true;

            let request = {...this.request, ...{offset: ((this.request.offset || 0) + this.players.length)}};

            this.playerService.filter(request)
                .subscribe(players => {
                    this.offsetScrollMarkers.push({
                        offset: request.offset, 
                        scroll: this.playersEl.nativeElement.offsetTop + this.playersEl.nativeElement.offsetHeight
                    });
                    
                    this.players = [...this.players, ...players];
                    this.loading = false;
                })
            ;
        }

        const offsetScrollMarkers = this.offsetScrollMarkers.filter(offsetScroll => {
            return offsetScroll.scroll < scrollTop + (screenHeight/2);
        });

        if(offsetScrollMarkers.length) {
            this.platformLocation.replaceState(null, "", 
                this.router.createUrlTree(["."], {"queryParams": {...this.request, ...{offset: offsetScrollMarkers.slice(-1)[0].offset}}, "relativeTo": this.route}).toString()
            );
        }
    }
}
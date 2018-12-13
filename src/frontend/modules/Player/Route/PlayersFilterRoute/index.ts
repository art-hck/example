import {Component, ElementRef, HostListener, ViewChild} from "@angular/core";
import {ActivatedRoute, Params, Router} from "@angular/router";
import {PlatformLocation} from "@angular/common";

import {Player} from "../../Entity/Player";
import {PlayerRESTService} from "../../Service/PlayerRESTService";
import {PlayerFilterRequest} from "../../Http/PlayerFilterRequest";
import {ParamsService} from "../../../Application/Service/ParamsService";
import {PlatformService} from "../../../Application/Service/PlatformService";
import {Throttle} from "../../../Application/Decorator/Throttle";
import {timer} from "rxjs/internal/observable/timer";
import {finalize, map, tap} from "rxjs/operators";
import {forkJoin} from "rxjs/internal/observable/forkJoin";

@Component({
    templateUrl: "./template.pug",
    styleUrls: ["./style.shadow.scss"],
})

export class PlayersFilterRoute {
    public players: Player[];
    public loading: boolean = false;
    private request: PlayerFilterRequest;
    private offsetScrollMarkers: { offset: number, scroll: number }[];
    private isScrollEnd: boolean = false;

    @ViewChild("playersEl") playersEl: ElementRef;

    constructor(
        private route: ActivatedRoute, 
        private playerService: PlayerRESTService,
        private platformLocation: PlatformLocation,
        private router: Router,
        private paramsService: ParamsService, 
        private pl: PlatformService) 
    {}
    
    ngOnInit() {
        this.request = this.paramsService.parse<PlayerFilterRequest>(this.route.snapshot.queryParams);
        this.offsetScrollMarkers = [{offset: this.request.offset || 0, scroll: 0}];
        this.route.data
            .pipe(tap(() => this.isScrollEnd = false))
            .subscribe(data => this.players = data.players)
        ;
    }

    @HostListener('window:scroll')
    @Throttle(300)
    onScroll() {
        if(this.pl.isPlatformServer() || this.isScrollEnd) return;
        
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const documentHeight = document.documentElement.scrollHeight;
        const screenHeight = document.documentElement.clientHeight;
        
        if(scrollTop + (screenHeight * 3) >= documentHeight - screenHeight && !this.loading) {
            this.loading = true;

            let request = {...this.request, ...{offset: ((this.request.offset || 0) + this.players.length)}};


            forkJoin(this.playerService.filter(request), timer(1000)) // Минимальный показ прелоадера
                .pipe(
                    map(([data]) => data),
                    finalize(() => this.loading = false)
                )
                .subscribe(players => {
                    if(players.length == 0) {
                        this.isScrollEnd = true;
                    }
                    
                    this.offsetScrollMarkers.push({
                        offset: request.offset,
                        scroll: this.playersEl.nativeElement.offsetTop + this.playersEl.nativeElement.offsetHeight
                    });
                    
                    this.players = [...this.players, ...players];
                })
            ;
        }

        const offsetScrollMarkers = this.offsetScrollMarkers.filter(offsetScroll => {
            return offsetScroll.scroll < scrollTop + (screenHeight/2);
        });

        if(offsetScrollMarkers.length) {
            let params: Params = {...this.route.snapshot.queryParams, ...{offset: offsetScrollMarkers.slice(-1)[0].offset}};
            if(params.offset == 0) delete params.offset; 
            let url = this.router.createUrlTree(["."], {"queryParams": params, "relativeTo": this.route}).toString();
            this.platformLocation.replaceState(null, "", url);
        }
    }
}
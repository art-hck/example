import {Injectable} from "@angular/core";
import {ActivatedRouteSnapshot, Resolve} from "@angular/router";
import {Observable} from "rxjs";
import {PlayerRESTService} from "../Service/PlayerRESTService";
import {PlayerFilterRequest} from "../Http/PlayerFilterRequest";

@Injectable()
export class PlayersFilterResolver implements Resolve<any> {
    constructor(private playerRESTService: PlayerRESTService) {}

    resolve(route: ActivatedRouteSnapshot): Observable<any> | any {
        if(Object.keys(route.queryParams).length === 0) 
            return undefined;
        
        let playerFilterRequest: PlayerFilterRequest = route.queryParams;

        //@TODO: check params!
        // if(route.queryParams.hasOwnProperty("dateFrom")) {}
        // if(route.queryParams.hasOwnProperty("dateTo")) {}
        // if(route.queryParams.hasOwnProperty("leagueId")) {}
        // if(route.queryParams.hasOwnProperty("teamId")) {}
        // if(route.queryParams.hasOwnProperty("minGoals")) {}
        // if(route.queryParams.hasOwnProperty("maxGoals")) {}
        // if(route.queryParams.hasOwnProperty("minCards")) {}
        // if(route.queryParams.hasOwnProperty("maxCards")) {}
        // if(route.queryParams.hasOwnProperty("cardsType")) {}
        // if(route.queryParams.hasOwnProperty("minPlayTime")) {}
        // if(route.queryParams.hasOwnProperty("maxPlayTime")) {}
        // if(route.queryParams.hasOwnProperty("orderBy")) {}
        // if(route.queryParams.hasOwnProperty("orderDirection")) {}
        // if(route.queryParams.hasOwnProperty("offset")) {}
        // if(route.queryParams.hasOwnProperty("limit")) {}

        return this.playerRESTService.filter(playerFilterRequest);
    }
}
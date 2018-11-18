import {Injectable} from "@angular/core";
import {ActivatedRouteSnapshot, Resolve} from "@angular/router";
import {Observable} from "rxjs";
import {PlayerRESTService} from "../Service/PlayerRESTService";
import {Player} from "../Entity/Player";

@Injectable()
export class PlayersFilterResolver implements Resolve<any>
{
    constructor(private playerService: PlayerRESTService) {}

    resolve(route: ActivatedRouteSnapshot): Observable<Player[]>
    {
        return this.playerService.filter(route.queryParams);
    }
}
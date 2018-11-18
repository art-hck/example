import {Injectable} from "@angular/core";
import {ActivatedRouteSnapshot, Resolve} from "@angular/router";
import {Observable} from "rxjs";
import {Player} from "../Entity/Player";
import {PlayerRESTService} from "../Service/PlayerRESTService";

@Injectable()
export class PlayerResolver implements Resolve<Player>
{
    constructor(private playerService: PlayerRESTService) {}

    resolve(route: ActivatedRouteSnapshot): Observable<Player>
    {
        return this.playerService.get(route.params["id"]);
    }
}
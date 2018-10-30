import {Injectable} from "@angular/core";
import {ActivatedRouteSnapshot, Resolve} from "@angular/router";
import {Observable} from "rxjs";
import {Player} from "../Entity/Player";
import {PlayerService} from "../Service/PlayerService";

@Injectable()
export class PlayerResolver implements Resolve<Player> {
    constructor(private playerService: PlayerService) {}

    resolve(route: ActivatedRouteSnapshot): Observable<Player> {
        return this.playerService.get(route.params["id"]);
    }
}
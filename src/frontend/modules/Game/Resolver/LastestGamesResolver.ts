import {Injectable} from "@angular/core";
import {ActivatedRouteSnapshot, Resolve} from "@angular/router";
import {Observable} from "rxjs";
import {GameRESTService} from "../Service/GameRESTService";
import {Game} from "../Entity/Game";

@Injectable()
export class LastestGamesResolver implements Resolve<Game[]>
{
    constructor(private gameService: GameRESTService) {}

    resolve(route: ActivatedRouteSnapshot): Observable<Game[]>
    {
        return this.gameService.getLastGames();
    }
}
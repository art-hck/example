import {Injectable} from "@angular/core";
import {ActivatedRouteSnapshot, Resolve} from "@angular/router";
import {Observable} from "rxjs";
import {GameRESTService} from "../../Game/Service/GameRESTService";
import {GameByPlayerResponse} from "../../Game/Http/GameByPlayerResponse";

@Injectable()
export class PlayerGamesResolver implements Resolve<GameByPlayerResponse> 
{
    constructor(public gameService: GameRESTService) {}
     
    resolve(route: ActivatedRouteSnapshot): Observable<GameByPlayerResponse>
    {
         return this.gameService.getByPlayer(route.params["id"]);
    }
}
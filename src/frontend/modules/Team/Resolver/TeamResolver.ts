import {Injectable} from "@angular/core";
import {ActivatedRouteSnapshot} from "@angular/router";
import {Observable} from "rxjs";
import {TeamRESTService} from "../Service/TeamRESTService";
import {Team} from "../Entity/Team";

@Injectable()
export class TeamResolver {
    constructor(private teamService: TeamRESTService) {}

    resolve(route: ActivatedRouteSnapshot): Observable<Team>
    {
        return this.teamService.get(route.params["id"]);
    }
}

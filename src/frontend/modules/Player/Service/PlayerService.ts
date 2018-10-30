import {Injectable} from "@angular/core";
import {Observable} from "rxjs";
import {catchError, tap} from "rxjs/internal/operators";

import {CacheService} from "../../Application/Service/CacheService";
import {Player} from "../Entity/Player";
import {PlayerRESTService} from "./PlayerRESTService";

@Injectable()
export class PlayerService {

    constructor(private rest: PlayerRESTService, private cacheService: CacheService){}

    public get(id: number): Observable<Player>
    {
        return this.cacheService.get<Player>(`Player-${id}`).pipe(
            catchError(() =>
                this.rest.get(id).pipe(
                    tap(player => 
                        this.cacheService.set(player, `Player-${id}`)
                    )
                )
            )
        );
    }
}
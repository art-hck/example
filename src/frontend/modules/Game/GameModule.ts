import {NgModule} from "@angular/core";
import {GameRESTService} from "./Service/GameRESTService";
import {LastestGamesResolver} from "./Resolver/LastestGamesResolver";

@NgModule({
    providers: [
        LastestGamesResolver,
        GameRESTService
    ],
})
export class GameModule {}
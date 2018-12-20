import {NgModule} from "@angular/core";
import {GameRESTService} from "./Service/GameRESTService";

@NgModule({
    providers: [
        GameRESTService
    ],
})
export class GameModule {}
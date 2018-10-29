import {NgModule} from "@angular/core";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {RouterModule} from "@angular/router";
import {CommonModule} from "@angular/common";

import {PlayerRESTService} from "./Service/PlayerRESTService";
import {PlayerRoute} from "./Route/PlayerRoute";
import {PlayerResolver} from "./Resolver/PlayerResolver";
import {PlayersFilterRoute} from "./Route/PlayersFilterRoute";
import {MaterialModule} from "../Application/MaterialModule";
import {PlayersFilterResolver} from "./Resolver/PlayersFilterResolver";
import {PlayersRoute} from "./Route/PlayersRoute";

@NgModule({
    imports: [
        CommonModule,
        FormsModule,
        RouterModule,
        ReactiveFormsModule,
        MaterialModule,    
    ],
    declarations: [
        PlayerRoute,
        PlayersRoute,
        PlayersFilterRoute,
    ],
    exports: [
    ],
    providers: [
        PlayerRESTService,
        PlayerResolver,
        PlayersFilterResolver,
    ]
})
export class PlayerModule {
}
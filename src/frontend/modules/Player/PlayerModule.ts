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
import {NouisliderModule} from "ng2-nouislider";
import {RangeSliderFormControl} from "./FormControl/RangeSliderFormControl";
import {CanActivatePlayerFilter} from "./CanActivate/CanActivatePlayerFilter";
import {PlayerGamesResolver} from "./Resolver/PlayerGamesResolver";

@NgModule({
    imports: [
        CommonModule,
        FormsModule,
        RouterModule,
        ReactiveFormsModule,
        MaterialModule,
        NouisliderModule,
    ],
    declarations: [
        PlayerRoute,
        PlayersRoute,
        PlayersFilterRoute,
        RangeSliderFormControl
    ],
    exports: [
    ],
    providers: [
        PlayerRESTService,
        PlayerResolver,
        PlayersFilterResolver,
        PlayerGamesResolver,
        CanActivatePlayerFilter
    ]
})
export class PlayerModule {
}
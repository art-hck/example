import {NgModule} from "@angular/core";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {RouterModule} from "@angular/router";
import {CommonModule} from "@angular/common";

import {PlayerRESTService} from "./Service/PlayerRESTService";
import {PlayerRoute} from "./Route/PlayerRoute";
import {PlayerResolver} from "./Resolver/PlayerResolver";
import {PlayerFilterRoute} from "./Route/PlayerFilterRoute";
import {MaterialModule} from "../Application/MaterialModule";

@NgModule({
    imports: [
        CommonModule,
        FormsModule,
        RouterModule,
        ReactiveFormsModule,
        MaterialModule        
    ],
    declarations: [
        PlayerRoute,
        PlayerFilterRoute
    ],
    exports: [
    ],
    providers: [
        PlayerRESTService,
        PlayerResolver
    ]
})
export class PlayerModule {
}
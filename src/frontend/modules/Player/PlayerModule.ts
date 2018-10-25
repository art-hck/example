import {NgModule} from "@angular/core";
import {PlayerRESTService} from "./Service/PlayerRESTService";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {RouterModule} from "@angular/router";
import {CommonModule} from "@angular/common";
import {PlayerRoute} from "./Route/PlayerRoute";
import {PlayerResolver} from "./Resolver/PlayerResolver";
import {PlayerFilterRoute} from "./Route/PlayerFilterRoute";

@NgModule({
    imports: [
        CommonModule,
        FormsModule,
        RouterModule,
        ReactiveFormsModule
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
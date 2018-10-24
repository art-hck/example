import {NgModule} from "@angular/core";
import {PlayerRESTService} from "./Service/PlayerRESTService";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {RouterModule} from "@angular/router";
import {CommonModule} from "@angular/common";
import {PlayerRoute} from "./Route/PlayerRoute";
import {PlayerResolver} from "./Resolver/PlayerResolver";

@NgModule({
    imports: [
        CommonModule,
        FormsModule,
        RouterModule,
        ReactiveFormsModule
    ],
    declarations: [
        PlayerRoute
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
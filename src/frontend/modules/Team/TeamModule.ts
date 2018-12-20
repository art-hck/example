import {NgModule} from "@angular/core";
import {TeamRESTService} from "./Service/TeamRESTService";
import {TeamResolver} from "./Resolver/TeamResolver";
import {CommonModule} from "@angular/common";
import {FormsModule, ReactiveFormsModule} from "@angular/forms";
import {RouterModule} from "@angular/router";
import {MaterialModule} from "../Application/MaterialModule";
import {TeamRoute} from "./Routes/TeamRoute";
import {TeamPlayersResolver} from "./Resolver/TeamPlayersResolver";
import {TeamLastGamesResolver} from "./Resolver/TeamLastGamesResolver";

@NgModule({
    imports:[
        CommonModule,
        FormsModule,
        RouterModule,
        ReactiveFormsModule,
        MaterialModule
    ],
    declarations: [
        TeamRoute
    ],
    providers: [
        TeamRESTService,
        TeamResolver,
        TeamPlayersResolver,
        TeamLastGamesResolver
    ]
})
export class TeamModule {} 
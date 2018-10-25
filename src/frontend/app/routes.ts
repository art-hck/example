import {GenieRoutes} from "../modules/Application/Entity/Route";
import {PlayerRoute} from "../modules/Player/Route/PlayerRoute";
import {PlayerResolver} from "../modules/Player/Resolver/PlayerResolver";
import {PlayerFilterRoute} from "../modules/Player/Route/PlayerFilterRoute";

export const appRoutes: GenieRoutes = [
    {
        path: '',
        redirectTo: '/players/filter',
        pathMatch: "full"
    },
    {
        path: "player/:id",
        component: PlayerRoute,
        resolve: {
            player: PlayerResolver
        },
        data: {
            title: "Player page",
            description: "Genie description"
        }
        
    },
    {
        path: "players/filter",
        component: PlayerFilterRoute,
        data: {
            title: "Genie  filter",
            description: "Genie description"
        }
    }
];
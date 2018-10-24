import {MainRoute} from "../modules/Application/Routes/MainRoute/MainRoute";
import {GenieRoutes} from "../modules/Application/Entity/Route";
import {PlayerRoute} from "../modules/Player/Route/PlayerRoute";
import {PlayerResolver} from "../modules/Player/Resolver/PlayerResolver";

export const appRoutes: GenieRoutes = [
    {
        path: '',
        component: MainRoute,
        data: {
            title: "Genie",
            description: "Genie description"
        }
    },
    {
        path: "player/:id",
        component: PlayerRoute,
        resolve: {
            player: PlayerResolver
        }
    }
];
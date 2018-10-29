import {GenieRoutes} from "../modules/Application/Entity/Route";
import {PlayerRoute} from "../modules/Player/Route/PlayerRoute";
import {PlayerResolver} from "../modules/Player/Resolver/PlayerResolver";
import {PlayersFilterRoute} from "../modules/Player/Route/PlayersFilterRoute";
import {PageNotFoundRoute} from "../modules/Application/Route/PageNotFoundRoute";
import {PlayersFilterResolver} from "../modules/Player/Resolver/PlayersFilterResolver";
import {PlayersRoute} from "../modules/Player/Route/PlayersRoute";

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
        path: "players",
        component: PlayersRoute, 
        children: [
            {
                path: "filter",
                component: PlayersFilterRoute,
                runGuardsAndResolvers: 'paramsOrQueryParamsChange',
                resolve: {
                    players: PlayersFilterResolver
                },
            }
        ],
        data: {
            title: "Genie  filter",
            description: "Genie description"
        }
    },
    {
        path: 'not-found',
        component: PageNotFoundRoute,
        data: {title: '404 - Now found'}
    },
    {
        path: '**',
        component: PageNotFoundRoute,
        data: {title: '404 - Now found'}
    }
];
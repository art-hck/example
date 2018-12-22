import {GenieRoutes} from "../modules/Application/Entity/Route";
import {PlayerRoute} from "../modules/Player/Route/PlayerRoute";
import {PlayerResolver} from "../modules/Player/Resolver/PlayerResolver";
import {PlayersFilterRoute} from "../modules/Player/Route/PlayersFilterRoute";
import {PageNotFoundRoute} from "../modules/Application/Route/PageNotFoundRoute";
import {PlayersFilterResolver} from "../modules/Player/Resolver/PlayersFilterResolver";
import {PlayersRoute} from "../modules/Player/Route/PlayersRoute";
import {CanActivatePlayerFilter} from "../modules/Player/CanActivate/CanActivatePlayerFilter";
import {TeamRoute} from "../modules/Team/Routes/TeamRoute";
import {TeamResolver} from "../modules/Team/Resolver/TeamResolver";
import {TeamPlayersResolver} from "../modules/Team/Resolver/TeamPlayersResolver";
import {TeamLastGamesResolver} from "../modules/Team/Resolver/TeamLastGamesResolver";
import {MarketRoute} from "../modules/Application/Route/MarketRoute";
import {GameByPlayerResponse} from "../modules/Game/Http/GameByPlayerResponse";
import {PlayerGamesResolver} from "../modules/Player/Resolver/PlayerGamesResolver";

export const appRoutes: GenieRoutes = [
    {
        path: '',
        redirectTo: '/market',
        pathMatch: "full"
    },
    {
        path: 'market',
        component: MarketRoute,
        data: {
            title: "Market view"
        }
    },
    {
        path: "player/:id",
        component: PlayerRoute,
        resolve: {
            player: PlayerResolver,
            playerGames: PlayerGamesResolver
        },
        data: {
            title: "Player page",
            description: "Genie description"
        }
        
    },
    {
        path: "players",
        component: PlayersRoute,
        data: {
            title: "Players",
            description: "Genie description"
        },
        children: [
            {
                path: "filter",
                component: PlayersFilterRoute,
                runGuardsAndResolvers: 'paramsOrQueryParamsChange',
                data: {
                    title: "Players"
                },
                resolve: {
                    playerFilterResponse: PlayersFilterResolver
                },
                canActivate: [CanActivatePlayerFilter]
            }
        ]
    },
    {
        path: "team/:id",
        component: TeamRoute,
        resolve: {
            team: TeamResolver,
            playerFilterResponse: TeamPlayersResolver,
            lastGames: TeamLastGamesResolver
        },
        data: {
            title: "Team page",
            description: "Team description"
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
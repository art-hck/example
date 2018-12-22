import {Game} from "../Entity/Game";

export type GameByPlayerResponse  = {
    games: Game[];
    cards: []; // @TODO implement!
    substitutions: [] // @TODO implement!
}[];
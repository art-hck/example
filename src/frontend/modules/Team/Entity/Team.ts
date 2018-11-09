import {Player} from "../../Player/Entity/Player";
import {Country} from "../../Country/Entity/Country";

export interface Team {
    id: number
    name: string
    alias: string
    preview: string
    created: string
    updated: string
    tmId: number
    country: Country
    players: Player[]
    teamGames: []
}
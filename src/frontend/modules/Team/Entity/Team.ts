import {Player} from "../../Player/Entity/Player";

export interface Team {
    id: number
    name: string
    alias: string
    preview: string
    created: string
    updated: string
    tmId: number
    country: {
        id: number
    }
    players: Player[]
    teamGames: []
}
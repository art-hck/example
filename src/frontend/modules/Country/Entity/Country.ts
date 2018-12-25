import {Player} from "../../Player/Entity/Player";

export interface Country {
    id: number
    name: string
    alias: string
    players: Player[]
}
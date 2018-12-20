import {Player} from "../../Player/Entity/Player";
import {Team} from "../../Team/Entity/Team";

export interface Transfer {
    id: number
    player: Player
    left_team?: Team
    join_team?: Team
    date?: string
    fee?: number
    mv?: number
}
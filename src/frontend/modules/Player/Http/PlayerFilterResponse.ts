import {Player} from "../Entity/Player";

export type PlayerFilterResponse = {
    player: Player
    goals_count?: number
    assists_count?: number
    cards_count?: number
    play_time_sum?: number
}[]
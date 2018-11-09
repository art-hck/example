import {PlayerRole} from "./PlayerRole";
import {Team} from "../../Team/Entity/Team";

export interface Player {
    first_name: string
    last_name: string
    native_name: string
    alias: string
    birthday: number
    birthPlace: string
    country_id: number
    nationality: number
    nationality_f: number
    nationality_m: number
    foot: string
    role: PlayerRole
    height: number
    number: number
    avatar: string
    created: number
    updated: number
    status: number
    link_to_tm: string
    contract_until: number
    contract_ext: number
    team: Team
    in_team: number
    twitter: string
    facebook: string
    instagram: string
    agents: string
    age: number
    goals_count: number,
    assists_count: number,
    play_time: number,
}
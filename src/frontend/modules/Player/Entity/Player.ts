import {PlayerRole} from "./PlayerRole";
import {Team} from "../../Team/Entity/Team";
import {Country} from "../../Country/Entity/Country";
        
export interface Player {
    id: number
    tm_id: number
    first_name: string
    last_name: string
    native_name: string
    alias: string
    birthday: string
    age: number
    birthPlace: string
    foot: number
    role: PlayerRole
    height: number
    number: number
    avatar: string
    twitter: string
    facebook: string
    instagram: string
    country: Country
    birth_country: Country
    team: Team
    cards: {}
    joined: string
    until: string
    transfers: {}
    views: number
    goals_count: number
    assists_count: number
    play_time: number
}
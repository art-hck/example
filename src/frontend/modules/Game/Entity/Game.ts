import {Team} from "../../Team/Entity/Team";
import {League} from "../../League/Entity/League";

export interface Game {
    id: number
    league: League
    day: number
    date: string
    duration: number
    score: string 
    stadium: any // @TODO: implement!
    referee: any // @TODO: implement!
    goals: any  // @TODO: implement!
    cards: any  // @TODO: implement!
    status: number 
    updated: string
    attendance: any
    teams: Team[] 
}
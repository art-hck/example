import {Country} from "../../Country/Entity/Country";

export interface League {
    id: number
    name: string
    country: Country
    is_international: boolean
}
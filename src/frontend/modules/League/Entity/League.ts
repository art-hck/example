export interface League {
    id: number
    name: string
    season: LeagueSeason
    is_international: boolean
}

export type GroupedLeague<T> = {groupBy: T, leagues: League[]};

export type LeagueSeason = number;
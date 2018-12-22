import { Pipe, PipeTransform } from '@angular/core';

@Pipe({ name: 'short_number' })
export class ShortNumberPipe implements PipeTransform {
    
    transform(value: any, ...args: any[]): any {
        const suffixes = ["", "k", "m", "b"];
        const decimals = args[0] || 2;

        if(value == 0) return value;
        const k = 1000,
            dm = decimals <= 0 ? 0 : decimals || 2,
            i = Math.floor(Math.log(value) / Math.log(k));
        
        return parseFloat((value / Math.pow(k, i)).toFixed(dm)).toFixed(2) + ' ' + suffixes[i];
    }
}
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
        
        // const suffixes = ["", "k", "m", "b"];
        // const suffixIndex = Math.floor((""+value).length/3);
        // let shortValue = parseFloat((suffixIndex != 0 ? (value / Math.pow(1000, suffixIndex)) : value).toPrecision(2));
        //
        //
        // if (shortValue % 1 != 0) {
        //     console.log("!23");
        //     shortValue = +shortValue.toFixed(1);
        // }
        // return shortValue+suffixes[suffixIndex];
    }
}
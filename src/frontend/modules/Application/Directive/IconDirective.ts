import {AfterViewInit, Directive, ElementRef, Input} from "@angular/core";

@Directive({
    selector: '[icon]'
})

export class IconDirective implements AfterViewInit {
    
    @Input() icon:string;
    
    constructor(private el: ElementRef) {}
    
    ngAfterViewInit() {
        if(this.icon) {
            this.el.nativeElement.style.backgroundImage = `url("${this.icon}")`;
        }
    }
}
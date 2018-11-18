import {Component, forwardRef, HostBinding, Input} from "@angular/core";
import {ControlValueAccessor, NG_VALUE_ACCESSOR} from "@angular/forms";

@Component({
    selector: "range-slider-form-control",
    templateUrl: "./template.pug",
    styleUrls: ["./style.shadow.scss"],
    providers: [{
        provide: NG_VALUE_ACCESSOR,
        useExisting: forwardRef(() => RangeSliderFormControl),
        multi: true,
    }]
})
export class RangeSliderFormControl implements ControlValueAccessor {
    @Input() label: string;
    @Input() min: number;
    @Input() max: number;
    @HostBinding('class.disabled') isDisabled: boolean;
    @HostBinding('class.disabled') isReseted: boolean;

    public onChange: (value: RangeSliderValue) => void;
    public onTouched: (value: RangeSliderValue) => void;
    public value: RangeSliderValue;

    registerOnChange(fn: (value: RangeSliderValue) => void): void {
        this.onChange = fn;
    }

    registerOnTouched(fn: (value: RangeSliderValue) => void): void {
        this.onTouched = fn;
    }

    setDisabledState(isDisabled: boolean): void {
        this.isDisabled = isDisabled;
    }

    writeValue(value: RangeSliderValue | null): void {
        this.value = value || [this.min, this.max];
        this.isReseted = value === null;
    }
}

type RangeSliderValue = [number, number];
import {throttle} from 'throttle-debounce';

export function Throttle(delay): MethodDecorator {
    return (target: any, propertyKey: string, descriptor: TypedPropertyDescriptor<any>) => {
        descriptor.value = throttle(delay, descriptor.value);
        return descriptor;
    };
}

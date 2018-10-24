// Polyfills
import 'core-js/es6';
import 'core-js/es7/reflect';
import 'zone.js/dist/zone';

if (process.env.ENV === 'production') {
  // Production
} else {
  // Development and test
  Error['stackTraceLimit'] = 1; //Infinity
  import ('zone.js/dist/long-stack-trace-zone');
}

// Main
import { platformBrowserDynamic } from '@angular/platform-browser-dynamic';
import { enableProdMode } from '@angular/core';

import {ApplicationModuleBrowser} from "../modules/Application/ApplicationModuleBrowser";

if (process.env.ENV === 'production') {
  enableProdMode();
}

document.addEventListener(
  'DOMContentLoaded', 
  () => platformBrowserDynamic()
        .bootstrapModule(ApplicationModuleBrowser)
        .catch(err => console.error(err))
);
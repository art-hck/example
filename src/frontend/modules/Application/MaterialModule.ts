import {NgModule} from "@angular/core";
import {
    MatAutocompleteModule,
    MatButtonModule,
    MatCardModule,
    MatDatepickerModule, MatExpansionModule,
    MatFormFieldModule,
    MatGridListModule,
    MatIconModule,
    MatInputModule, MatListModule,
    MatMenuModule, MatNativeDateModule, MatProgressBarModule, MatProgressSpinnerModule,
    MatSelectModule,
    MatSidenavModule,
    MatSliderModule, MatTableModule,
    MatToolbarModule
} from "@angular/material";

@NgModule({
    exports: [
        MatAutocompleteModule,
        MatButtonModule,
        MatCardModule,
        MatDatepickerModule,
        MatFormFieldModule,
        MatGridListModule,
        MatIconModule,
        MatInputModule,
        MatMenuModule,
        MatSelectModule,
        MatSidenavModule,
        MatSliderModule,
        MatToolbarModule,
        MatNativeDateModule,
        MatExpansionModule,
        MatProgressSpinnerModule,
        MatListModule,
        MatTableModule,
        MatProgressBarModule
    ]
})
export class MaterialModule {
} 
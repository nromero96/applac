const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

 mix.options({
    processCssUrls: false
});

 /*
    ========================
            Assets
    ========================
*/

mix.sass('resources/sass/light/assets/main.scss', 'public/assets/css/light')
    .sass('resources/sass/dark/assets/main.scss', 'public/assets/css/dark')

    .sass('resources/sass/light/assets/front_form.scss', 'public/assets/css/light')

    .sass('resources/sass/layouts/vertical-light-menu/light/loader.scss', 'public/layouts/vertical-light-menu/css/light')
    .sass('resources/sass/layouts/vertical-light-menu/dark/loader.scss', 'public/layouts/vertical-light-menu/css/dark')

    .sass('resources/sass/layouts/vertical-light-menu/light/structure.scss', 'public/layouts/vertical-light-menu/css/light')
    .sass('resources/sass/layouts/vertical-light-menu/dark/structure.scss', 'public/layouts/vertical-light-menu/css/dark')

    // Invoice List
    .sass('resources/sass/light/assets/apps/invoice-list.scss', 'public/assets/css/light/apps')
    .sass('resources/sass/dark/assets/apps/invoice-list.scss', 'public/assets/css/dark/apps')

    // Authentication
    .sass('resources/sass/light/assets/authentication/auth-cover.scss', 'public/assets/css/light/authentication')
    .sass('resources/sass/dark/assets/authentication/auth-cover.scss', 'public/assets/css/dark/authentication')
    
    .sass('resources/sass/light/assets/authentication/auth-boxed.scss', 'public/assets/css/light/authentication')
    .sass('resources/sass/dark/assets/authentication/auth-boxed.scss', 'public/assets/css/dark/authentication')


    // Components
    .sass('resources/sass/light/assets/components/list-group.scss','public/assets/css/light/components')
    .sass('resources/sass/dark/assets/components/list-group.scss','public/assets/css/dark/components')

    // Users
    .sass('resources/sass/light/assets/users/users-list.scss','public/assets/css/light/users')
    .sass('resources/sass/dark/assets/users/users-list.scss','public/assets/css/dark/users')

    // Notes
    .sass('resources/sass/light/assets/apps/notes.scss','public/assets/css/light/apps')
    .sass('resources/sass/dark/assets/apps/notes.scss','public/assets/css/dark/apps')

    // Dashboard
    .sass('resources/sass/light/assets/dashboard/dash_1.scss','public/assets/css/light/dashboard')
    .sass('resources/sass/dark/assets/dashboard/dash_1.scss','public/assets/css/dark/dashboard')
    .sass('resources/sass/light/assets/dashboard/dash_2.scss','public/assets/css/light/dashboard')
    .sass('resources/sass/dark/assets/dashboard/dash_2.scss','public/assets/css/dark/dashboard')





    /*
        ========================
                Plugins
        ========================
    */

    //Apex
    .sass('resources/sass/light/plugins/apex/custom-apexcharts.scss','public/plugins/css/light/apex')
    .sass('resources/sass/dark/plugins/apex/custom-apexcharts.scss','public/plugins/css/dark/apex')

    //Autocomplete
    .sass('resources/sass/light/plugins/autocomplete/css/custom-autoComplete.scss','public/plugins/css/light/autocomplete/css')
    .sass('resources/sass/dark/plugins/autocomplete/css/custom-autoComplete.scss','public/plugins/css/dark/autocomplete/css')

    //Bootstrap range slider
    .sass('resources/sass/light/plugins/bootstrap-range-Slider/bootstrap-slider.scss','public/plugins/css/light/bootstrap-range-Slider')
    .sass('resources/sass/dark/plugins/bootstrap-range-Slider/bootstrap-slider.scss','public/plugins/css/dark/bootstrap-range-Slider')

    //fullcalendar
    .sass('resources/sass/light/plugins/fullcalendar/custom-fullcalendar.scss','public/plugins/css/light/fullcalendar')
    .sass('resources/sass/dark/plugins/fullcalendar/custom-fullcalendar.scss','public/plugins/css/dark/fullcalendar')

    //suppliers
    .sass('resources/sass/light/assets/apps/suppliers.scss','public/assets/css/light/apps')
    .sass('resources/sass/dark/assets/apps/suppliers.scss','public/assets/css/dark/apps')

    //tagify
    .sass('resources/sass/light/plugins/tagify/custom-tagify.scss','public/plugins/css/light/tagify')
    .sass('resources/sass/dark/plugins/tagify/custom-tagify.scss','public/plugins/css/dark/tagify')

    //bsStepper
    .sass('resources/sass/light/plugins/stepper/custom-bsStepper.scss','public/plugins/css/light/stepper')
    .sass('resources/sass/dark/plugins/stepper/custom-bsStepper.scss','public/plugins/css/dark/stepper')

    //table
    .sass('resources/sass/light/plugins/table/datatable/dt-global_style.scss','public/plugins/css/light/table/datatable')
    .sass('resources/sass/dark/plugins/table/datatable/dt-global_style.scss','public/plugins/css/dark/table/datatable')

    // Habilitar source maps solo en desarrollo
if (!mix.inProduction()) {
    mix.sourceMaps();
}

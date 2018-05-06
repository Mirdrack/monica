let mix = require('laravel-mix');

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

mix
    .copy('node_modules/admin-lte/dist/css/AdminLTE.min.css', 'public/css/adminlte')
    .copy('node_modules/admin-lte/dist/css/skins/skin-blue.min.css', 'public/css/adminlte/skins')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .copy('node_modules/admin-lte/dist/js/app.min.js', 'public/js/adminlte')
    .copy('resources/assets/js/main.js', 'public/js')
    .copy('node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js', 'public/js')
    .copy('node_modules/admin-lte/plugins/select2/select2.full.min.js', 'public/js')
    .copy('node_modules/admin-lte/plugins/select2/select2.min.css', 'public/css')

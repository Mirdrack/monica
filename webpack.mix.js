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

mix.js('resources/assets/js/app.js', 'public/js')
	.copy('node_modules/admin-lte/dist/css/AdminLTE.min.css', 'public/css/adminlte')
	.copy('node_modules/admin-lte/dist/css/skins/skin-blue.min.css', 'public/css/adminlte/skins')
	.sass('resources/assets/sass/app.scss', 'public/css');

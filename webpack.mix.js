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
   //.js('resources/assets/js/mapa.js', 'public/js')
   .js('resources/assets/js/infoelectoral.js', 'public/js')
   .js('resources/assets/js/lideres.js', 'public/js')
   .js('resources/assets/js/compromisos.js', 'public/js')
   .js('resources/assets/js/corporaciones.js', 'public/js')
   .js('resources/assets/js/visitas.js', 'public/js')
   .js('resources/assets/js/puestosvotacion.js', 'public/js')
   .js('resources/assets/js/usuarios.js', 'public/js')
   .js('resources/assets/js/permisos.js', 'public/js')
   .js('resources/assets/js/roles.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');

// const mix = require('laravel-mix');

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

// mix.js('resources/js/app.js', 'public/js')
//     .sass('resources/sass/app.scss', 'public/css')
//     .vue();

const mix = require("laravel-mix");

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
  .js("resources/js/app.js", "public/js")
  .sass("resources/sass/app.scss", "public/css")
  .vue();

mix.combine(
  [
    "node_modules/bootstrap/dist/css/bootstrap.min.css",
    "node_modules/@fortawesome/fontawesome-free/css/all.min.css",
    "node_modules/jquery-ui/themes/base/datepicker.css",
    "public/css/frontend.css",
  ],
  "public/css/frontend-mix.css"
);

mix.combine(
  [
    "node_modules/jquery/dist/jquery.min.js",
    "node_modules/bootstrap/dist/js/bootstrap.min.js",
    "node_modules/@fortawesome/fontawesome-free/js/all.min.js",
    "node_modules/jquery-ui/ui/widgets/datepicker.js",
  ],
  "public/js/frontend-mix.js"
);

mix.combine(
  [
    "node_modules/bootstrap/dist/css/bootstrap.min.css",
    "node_modules/@fortawesome/fontawesome-free/css/all.min.css",
    "node_modules/admin-lte/dist/css/adminlte.min.css",
  ],
  "public/css/backend-mix1.css"
);

mix.combine(
  [
    "node_modules/jquery/dist/jquery.min.js",
    "node_modules/bootstrap/dist/js/bootstrap.bundle.min.js",
    "node_modules/@fortawesome/fontawesome-free/js/all.min.js",
    "node_modules/admin-lte/dist/js/adminlte.min.js",
  ],
  "public/js/backend-mix1.js"
);

mix.browserSync({
  proxy: "http://localhost:8083", // Cambia a la URL que tu servidor de Laravel esté usando en Docker
  host: "localhost", // Host que es accesible desde tu máquina
  port: 8083,
  open: false, // Para evitar que abra una nueva ventana del navegador automáticamente
  notify: false, // Desactivar notificaciones en el navegador
});

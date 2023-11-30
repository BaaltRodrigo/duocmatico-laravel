const mix = require('laravel-mix');

mix.js('resources/js/main.js', 'public/js')
   .vue()
   .sass('resources/sass/app.scss', 'public/css');

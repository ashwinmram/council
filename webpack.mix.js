let mix = require('laravel-mix');

require('laravel-mix-tailwind');

mix.scripts([
    'resources/assets/js/dropzone.js'
], 'public/js/vendor.js');

mix
    .js('resources/assets/js/app.js', 'js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .tailwind()
    .browserSync('council.test');

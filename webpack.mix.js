let mix = require('laravel-mix');

mix.js([
        'resources/assets/vue/js/app.js',
        'resources/assets/vue/js/base/bars.js'
    ], 'public/vue/js')
    .sass(
        'resources/assets/vue/sass/app.scss',
        'public/vue/css'
    )
    .styles([
        'resources/assets/css/bootstrap-modern.css',
        'resources/assets/css/app-modern.css'
    ], 'public/vue/css/all.css')
    .webpackConfig({
        output: {
            chunkFilename: 'js/chunks/[name].js?id=[chunkhash]',
        }
    });